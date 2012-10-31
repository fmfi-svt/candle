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

  protected function warning($message) {
      if (!$this->warningsAsErrors) {
          $this->logBlock($message, 'INFO');
          $this->warningCount += 1;
      }
      else {
          throw new Exception($message);
      }
  }
  
  protected function deleteAllData() {
    $this->logSection('candle', 'Deleting all data');

    $deletedLessons = Doctrine::getTable('Lesson')->createQuery()
                            ->delete()->execute();

    $deletedLessonTypes = Doctrine::getTable('LessonType')->createQuery()
                            ->delete()->execute();

    $deletedRooms = Doctrine::getTable('Room')->createQuery()
                            ->delete()->execute();

    $deletedRoomTypes = Doctrine::getTable('RoomType')->createQuery()
                            ->delete()->execute();

    $deletedTeachers = Doctrine::getTable('Teacher')->createQuery()
                            ->delete()->execute();

    $deletedSubjects = Doctrine::getTable('Subject')->createQuery()
                            ->delete()->execute();

    $deletedStudentGroups = Doctrine::getTable('StudentGroup')->createQuery()
                            ->delete()->execute();
  }

  protected function debugDumpTables() {
      $this->logSection('candle', 'Dumping temporary tables into debug tables');

      $tables = array('tmp_insert_lesson_type', 'tmp_insert_room_type',
          'tmp_insert_teacher', 'tmp_insert_room', 'tmp_insert_subject',
          'tmp_insert_lesson', 'tmp_insert_lesson_teacher',
          'tmp_insert_lesson_student_group', 'tmp_insert_lesson_link');

      foreach ($tables as $table) {
          $this->executeSQL('DROP TABLE IF EXISTS debug_'.$table);
          $this->executeSQL('CREATE TABLE debug_'.$table.' SELECT * FROM '.$table);
      }
  }

  protected function mergeLessonTypes() {
      $this->logSection('candle', 'Merging lesson types');

      $sql = 'UPDATE lesson_type t, tmp_insert_lesson_type i';
      $sql .= ' SET t.name=i.name WHERE t.code = i.code';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO lesson_type (name, code) SELECT name, code';
      $sql .= ' FROM tmp_insert_lesson_type i';
      $sql .= ' WHERE i.code NOT IN (SELECT code FROM lesson_type)';
      $this->executeSQL($sql);
  }

  protected function mergeRoomTypes() {
      $this->logSection('candle', 'Merging room types');

      $sql = 'UPDATE room_type r, tmp_insert_room_type i';
      $sql .= ' SET r.name=i.name WHERE r.code = i.code';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO room_type (name, code) SELECT name, code';
      $sql .= ' FROM tmp_insert_room_type i';
      $sql .= ' WHERE i.code NOT IN (SELECT code FROM room_type)';
      $this->executeSQL($sql);
  }

  protected function mergeTeachers() {
      $this->logSection('candle', 'Merging teachers');

      $sql = 'UPDATE teacher t, tmp_insert_teacher i';
      $sql .= ' SET t.given_name=i.given_name, ';
      $sql .= ' t.family_name = i.family_name, ';
      $sql .= ' t.iniciala = i.iniciala, ';
      $sql .= ' t.oddelenie = i.oddelenie, ';
      $sql .= ' t.katedra = i.katedra, ';
      $sql .= ' t.login = i.login';
      $sql .= ' WHERE t.external_id = i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO teacher (given_name, family_name, ';
      $sql .= ' iniciala, oddelenie, katedra, external_id, login) ';
      $sql .= ' SELECT given_name, family_name, iniciala, oddelenie, ';
      $sql .= ' katedra, external_id, login';
      $sql .= ' FROM tmp_insert_teacher i';
      $sql .= ' WHERE i.external_id NOT IN (SELECT external_id FROM teacher)';
      $this->executeSQL($sql);
  }

  protected function mergeRooms() {
      $this->logSection('candle', 'Merging rooms');

      $sql = 'UPDATE room r, tmp_insert_room i, room_type rt';
      $sql .= ' SET r.room_type_id=rt.id, ';
      $sql .= ' r.capacity = i.capacity ';
      $sql .= ' WHERE r.name = i.name AND rt.code = i.room_type';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO room (name, room_type_id, ';
      $sql .= ' capacity) ';
      $sql .= 'SELECT i.name, rt.id, i.capacity';
      $sql .= ' FROM tmp_insert_room i, room_type rt';
      $sql .= ' WHERE rt.code=i.room_type AND i.name NOT IN (SELECT name FROM room)';
      $this->executeSQL($sql);
  }

  protected function mergeSubjects() {
      $this->logSection('candle', 'Merging subjects');

      $sql = 'UPDATE subject s, tmp_insert_subject i';
      $sql .= ' SET s.name=i.name, s.code=i.code, s.short_code=i.short_code, ';
      $sql .= ' s.credit_value=i.credit_value, s.rozsah=i.rozsah';
      $sql .= ' WHERE s.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO subject (name, code, short_code, ';
      $sql .= ' credit_value, rozsah, external_id) ';
      $sql .= 'SELECT i.name, i.code, i.short_code, i.credit_value, i.rozsah, ';
      $sql .= ' i.external_id';
      $sql .= ' FROM tmp_insert_subject i';
      $sql .= ' WHERE i.external_id NOT IN (SELECT external_id FROM subject)';
      $this->executeSQL($sql);
  }

  protected function mergeLessons() {
      $this->logSection('candle', 'Merging lessons');

      $sql = 'UPDATE lesson l, tmp_insert_lesson i, lesson_type lt, room r, subject s';
      $sql .= ' SET l.day=i.day, ';
      $sql .= ' l.start=i.start, l.end=i.end, ';
      $sql .= ' l.lesson_type_id=lt.id, l.room_id=r.id, l.subject_id=s.id, ';
      $sql .= ' l.note=i.note ';
      $sql .= ' WHERE lt.code = i.lesson_type AND r.name = i.room ';
      $sql .= ' AND s.external_id=i.subject AND l.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO lesson (day, start, end, lesson_type_id, room_id, ';
      $sql .= ' subject_id, external_id, note) ';
      $sql .= 'SELECT i.day, i.start, i.end, lt.id, r.id, s.id, i.external_id, ';
      $sql .= ' i.note ';
      $sql .= ' FROM tmp_insert_lesson i, lesson_type lt, room r, subject s';
      $sql .= ' WHERE lt.code = i.lesson_type AND r.name = i.room ';
      $sql .= ' AND s.external_id=i.subject ';
      $sql .= ' AND i.external_id NOT IN (SELECT external_id FROM lesson)';
      $this->executeSQL($sql);

      $sql = 'DELETE FROM tl USING teacher_lessons tl, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE tl.lesson_id=l.id AND l.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO teacher_lessons (teacher_id, lesson_id) ' ;
      $sql .= ' SELECT t.id, l.id ';
      $sql .= ' FROM lesson l, teacher t, tmp_insert_lesson_teacher i';
      $sql .= ' WHERE l.external_id=i.lesson_external_id AND t.external_id=i.teacher_external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO student_group (name)';
      $sql .= ' SELECT DISTINCT i.student_group';
      $sql .= ' FROM tmp_insert_lesson_student_group i';
      $sql .= ' WHERE i.student_group NOT IN (SELECT name from student_group)';
      $this->executeSQL($sql);

      $sql = 'DELETE FROM sgl';
      $sql .= ' USING student_group_lessons sgl, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE sgl.lesson_id=l.id ';
      $sql .= ' AND l.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO student_group_lessons (student_group_id, lesson_id)';
      $sql .= ' SELECT sg.id, l.id';
      $sql .= ' FROM student_group sg, lesson l, tmp_insert_lesson_student_group i';
      $sql .= ' WHERE sg.name=i.student_group AND l.external_id=i.lesson_external_id';
      $this->executeSQL($sql);

      $sql = 'DELETE FROM ll';
      $sql .= ' USING linked_lessons ll, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE (ll.lesson1_id=l.id OR ll.lesson2_id=l.id)';
      $sql .= ' AND l.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO linked_lessons (lesson1_id, lesson2_id)';
      $sql .= ' SELECT l1.id, l2.id';
      $sql .= ' FROM lesson l1, lesson l2, tmp_insert_lesson_link i';
      $sql .= ' WHERE l1.external_id=i.lesson1_external_id ';
      $sql .= ' AND l2.external_id=i.lesson2_external_id';
      $this->executeSQL($sql);

  }

  protected function deleteExcessSubjects() {
      $this->logSection('candle', 'Removing excess subjects (not present in xml)');

      $sql = 'DELETE FROM s';
      $sql .= ' USING subject s';
      $sql .= ' WHERE s.external_id NOT IN (SELECT s.external_id FROM tmp_insert_subject s)';
      $this->executeSQL($sql);
  }

  protected function deleteExcessLessons() {
      $this->logSection('candle', 'Removing excess lessons (not present in xml)');

      // TODO: mozno nemazat, ale len pridat flag vymazane, alebo nejaky zaznam do historie

      $sql = 'DELETE FROM l';
      $sql .= ' USING lesson l';
      $sql .= ' WHERE l.external_id NOT IN (SELECT i.external_id FROM tmp_insert_lesson i)';
      $this->executeSQL($sql);
  }
  
  protected function checkVersion() {
      $this->logSection('candle', 'Checking version information');
      
      // TODO: Kontrolovat, ci je semester a skol.rok rovnaky!!!
      
      $sql = 'SELECT MAX(version) as max_version FROM data_update';
      $prepared = $this->connection->prepare($sql);
      $prepared->execute();
      $data = $prepared->fetchAll();
      
      if (count($data) != 1) {
          $this->warning('Failed retrieving max version, row# != 1');
          return;
      }
      
      $max_version = $data[0]['max_version'];
      if ($max_version == null) {
          // no previous version stored
          return;
      }
      
      if (date('Y-m-d H:i:s', $this->version) <= $max_version) {
          $this->warning('You are probably trying to import version older than already stored in database');
      }
  }

  protected function insertDataUpdate() {
      $this->logSection('candle', 'Inserting information about data update');

      $sql = 'INSERT INTO data_update';
      $sql .= ' (datetime, description, version, semester, school_year_low)';
      $sql .= ' VALUES (NOW(), ?, ?, ?, ?)';
      
      $pos = strpos($this->importer->getSkolrok(), '/');
      if ($pos === false) {
          $skolrok = intval($this->importer->getSkolrok());
      }
      else {
          $skolrok = intval(substr($this->importer->getSkolrok(), 0, $pos));
      }

      $prepared = $this->connection->prepare($sql);
      $data = array($this->updateDescription,
          date('Y-m-d H:i:s', $this->importer->getVersion()),
          $this->importer->getSemester(),
          $skolrok);
      $this->executePreparedSQL($prepared, $data);

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

    $this->importer = new RozvrhXMLImporter($this->connection);
    $this->parser = new RozvrhXMLParser($this->importer);
    
    // Sice som uz znizil pamatove naroky, stale treba zvysit limit
    ini_set("memory_limit",$options['memory']);

    // Tieto DDL statementy musia byt pred createTransaction,
    // pretoze MySQL automaticky commituje transakciu po akomkolvek
    // DDL (aj napriek tomu, ze su to docasne tabulky)
    $this->importer->prepareDatabase();

    $this->doctrineConnection->beginTransaction();
    $error = false;

    try {
        $this->importer->prepareTransaction();

        $this->logSection('candle', 'Loading xml data into database');
        // TODO: feedovat parser po castiach
        $this->parser->parse(file_get_contents($arguments['file']), true);

        if ($options['replace']) {
            $this->deleteAllData();
        }

        if ($options['debug-dump-tables']) {
            $this->debugDumpTables();
        }

        if (!$options['no-merges']) {
            $this->mergeLessonTypes();
            $this->mergeRoomTypes();
            $this->mergeTeachers();
            $this->mergeRooms();
            $this->mergeSubjects();
            $this->mergeLessons();
        }

        if (!$options['no-removes']) {
            $this->deleteExcessLessons();
            $this->deleteExcessSubjects();
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
        
        $this->checkVersion();
        $this->insertDataUpdate();

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
