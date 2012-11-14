<?php

/**

    Copyright 2010, 2011, 2012 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */


class CandleImportTask extends sfBaseTask
{

  /**
   *
   * @var PDO
   */
  protected $connection;

  protected function configure()
  {
    $this->addArguments(array(
        new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'The file to load'),
    ));    
    
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', 'frontend'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environement', 'prod'),
        new sfCommandOption('replace', null, sfCommandOption::PARAMETER_NONE, 'Whether to replace data instead of merge'),
        new sfCommandOption('dry-run', null, sfCommandOption::PARAMETER_NONE, 'Don\'t modify data tables'),
        new sfCommandOption('no-merges', null, sfCommandOption::PARAMETER_NONE, 'Don\'t perform merge stage'),
        new sfCommandOption('ignore-warnings', null, sfCommandOption::PARAMETER_NONE, 'Ignore any warnings (i.e. don\'t ask about them at the end)'),
        new sfCommandOption('print-sql', null, sfCommandOption::PARAMETER_NONE, 'Print SQL commands executed'),
        new sfCommandOption('debug-dump-tables', null, sfCommandOption::PARAMETER_NONE, 'Create a persistent copy of temporary tables'),
        new sfCommandOption('warnings-as-errors', null, sfCommandOption::PARAMETER_NONE, 'Treat warnings as errors'),
        new sfCommandOption('no-removes', null, sfCommandOption::PARAMETER_NONE, 'Don\'t remove objects in db that were deleted from xml'),
        new sfCommandOption('message', null, sfCommandOption::PARAMETER_OPTIONAL, 'Description of the update', ''),
        new sfCommandOption('no-recalculate-free-rooms', null, sfCommandOption::PARAMETER_NONE, 'Don\'t recalculate free room intervals'),
        new sfCommandOption('memory', null, sfCommandOption::PARAMETER_OPTIONAL, 'Memory limit for this task, e.g. 128M', '128M'),
        new sfCommandOption('no-generate-slugs', null, sfCommandOption::PARAMETER_NONE, 'Don\'t generate slugs'),
    ));
 
    $this->namespace = 'candle';
    $this->name = 'import';
    $this->briefDescription = 'Load rozvrh.xml with lesson definitions';
 
    $this->detailedDescription = <<<EOF
The [candle:import|INFO] task loads the rozvrh.xml into database,
replacing any definitions currently there:
 
  [./symfony candle:import --env=prod ~/rozvrh.xml|INFO]
EOF;
    
    $this->warnings = 0;
    
  }
    
  protected function executeSQL($sql) {
      if ($this->printSQL) {
          echo $sql;
          echo ";\n";
      }
      return $this->connection->exec($sql);
  }

  protected function executePreparedSQL($prepared, array $params) {
      return $prepared->execute($params);
  }

  public function warning($message) {
      if (!$this->warningsAsErrors) {
          $this->logBlock($message, 'INFO');
          $this->warningCount += 1;
      }
      else {
          throw new Exception($message);
      }
  }
  
  protected function execute($arguments = array(), $options = array())
  {

    $databaseManager = new sfDatabaseManager($this->configuration);
    $this->doctrineConnection = Doctrine_Manager::connection();
    $this->connection = $this->doctrineConnection->getDbh();
    $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';
    $this->dataField = null;
    $this->currentElementPath = array();
    $this->printSQL = $options['print-sql'];
    $this->warningsAsErrors = $options['warnings-as-errors'];
    $this->updateDescription = $options['message'];
    $this->warningCount = 0;
    $this->ignoreWarnings = $options['ignore-warnings'];

    $messageToAsk = 'This command will update source timetable data in the "%s" connection.';

    if ($options['replace']) {
        $messageToAsk = 'This command will DELETE ALL timetable data in the "%s" connection.';
    }

    if (!$this->askConfirmation(array_merge(
        array(sprintf($messageToAsk, $environment), ''),
        array('', 'Are you sure you want to proceed? (y/N)')
      ), 'QUESTION_LARGE', false)
    )
    {
      $this->logSection('candle', 'task aborted');

      return 1;
    }

    $this->xmlImporter = new RozvrhXMLImporter($this->connection);
    $this->parser = new RozvrhXMLParser($this->xmlImporter);
    $this->candleImporter = new CandleImporter($this->connection, $this);
    
    // Sice som uz znizil pamatove naroky, stale treba zvysit limit
    ini_set("memory_limit",$options['memory']);

    // Tieto DDL statementy musia byt pred createTransaction,
    // pretoze MySQL automaticky commituje transakciu po akomkolvek
    // DDL (aj napriek tomu, ze su to docasne tabulky)
    $this->xmlImporter->prepareDatabase();

    $this->doctrineConnection->beginTransaction();
    $error = false;

    try {
        $this->xmlImporter->prepareTransaction();

        $this->logSection('candle', 'Loading xml data into database');
        $file = fopen($arguments['file'], 'r');
        if ($file === false) {
            throw new Exception('Failed to open file');
        }
        
        while (!feof($file)) {
            $data = fread($file, 4096);
            if ($data === false) {
                throw new Exception('Failed reading file');
            }
            $this->parser->parse($data, false);
        }
        $this->parser->parse('', true);
        fclose($file);
        
        if ($options['replace']) {
            $this->logSection('candle', 'Deleting all data');
            $this->candleImporter->deleteAllData();
        }

        if ($options['debug-dump-tables']) {
            $this->logSection('candle', 'Dumping temporary tables into debug tables');
            $this->candleImporter->debugDumpTables();
        }

        if (!$options['no-merges']) {
            $this->logSection('candle', 'Merging lesson types');
            $this->candleImporter->mergeLessonTypes();

            $this->logSection('candle', 'Merging room types');
            $this->candleImporter->mergeRoomTypes();

            $this->logSection('candle', 'Merging teachers');
            $this->candleImporter->mergeTeachers();

            $this->logSection('candle', 'Merging rooms');
            $this->candleImporter->mergeRooms();

            $this->logSection('candle', 'Merging subjects');
            $this->candleImporter->mergeSubjects();

            $this->logSection('candle', 'Merging lessons');
            $this->candleImporter->mergeLessons();
        }

        if (!$options['no-removes']) {
            $this->logSection('candle', 'Removing excess lessons (not present in xml)');
            $this->candleImporter->deleteExcessLessons();
            $this->logSection('candle', 'Removing excess subjects (not present in xml)');
            $this->candleImporter->deleteExcessSubjects();
        }

        if (!$options['no-recalculate-free-rooms']) {
            $this->logSection('candle', 'Calculating free room intervals');
            Doctrine::getTable('FreeRoomInterval')->calculate();
        }
        
        if (!$options['no-generate-slugs']) {
            $this->logSection('candle', 'Calculating slugs');

            $slugifier = new TableSlugifier($this->connection);
            $slugifier->slugifyTable('teacher', array('given_name', 'family_name'));
        }
        
        $version = $this->xmlImporter->getVersion();

        $this->logSection('candle', 'Checking version information');
        $this->candleImporter->checkVersion($version);

        $this->logSection('candle', 'Inserting information about data update');
        $this->candleImporter->insertDataUpdate($this->updateDescription,
                $version);

        $commit = true;

        if ($options['dry-run']) {
            $this->logSection('candle', 'This is dry run, so i\'ll rolback this transaction');
            $commit = false;
        }
        else {
            if (!$this->ignoreWarnings && $this->warningCount > 0) {
                if (!$this->askConfirmation(array_merge(
                    array(sprintf('%d warning(s) occured', $this->warningCount), ''),
                    array('', 'Are you sure you want to commit this transaction? (y/N)')
                  ), 'QUESTION_LARGE', false)
                ) {
                    $commit = false;
                }
            }
        }

        if ($commit) {
            $this->logSection('candle', 'Committing transaction');
            $this->doctrineConnection->commit();
        }
        else {
            $this->logSection('candle', 'Rolling back transaction');
            $this->doctrineConnection->rollback();
        }

        $this->logSection('Done.');
    }
    catch (Exception $e) {
        $this->logSection('candle', 'Exception occured, executing rollback');
        $this->logBlock(array($e->getMessage(), $e->getTraceAsString()), 'ERROR');
        $this->doctrineConnection->rollback();
        $error = true;
    }

    $this->logSection(sprintf('%d warning(s)', $this->warningCount));
    
    $this->parser->close();

    if ($error) {
        return 1;
    }

  }
}
