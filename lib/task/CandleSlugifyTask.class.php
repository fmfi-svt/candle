<?php

/**

    Copyright 2012 Martin Sucha

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


class CandleSlugifyTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', 'frontend'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environement', 'prod'),
        new sfCommandOption('dry-run', null, sfCommandOption::PARAMETER_NONE, 'Don\'t modify data tables'),
        new sfCommandOption('slug-column', null, sfCommandOption::PARAMETER_REQUIRED, 'Slug column', 'slug'),
    ));
    $this->addArguments(array(
        new sfCommandArgument('table', sfCommandArgument::REQUIRED, 'Model name'),
        new sfCommandArgument('columns', sfCommandArgument::IS_ARRAY, 'Column names'),
    ));

    $this->namespace = 'candle';
    $this->name = 'slugify';
    $this->briefDescription = 'Generate slugs for a table';

    $this->detailedDescription = <<<EOF
The [candle:slugify|INFO] task calculates slugs for a table

  [./symfony candle:slugify Teacher given_name family_name --env=prod|INFO]
EOF;

  }

  protected function execute($arguments = array(), $options = array())
  {

    $databaseManager = new sfDatabaseManager($this->configuration);
    $this->connection = Doctrine_Manager::connection();
    $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';

    $this->connection->beginTransaction();
    $slugifier = new TableSlugifier($this->connection);
    try {
        $this->logSection('candle', 'Calculating slugs');

        $table = $arguments['table'];
        $slugColumn = $options['slug-column'];
        $columns = $arguments['columns'];
        $slugifier->slugifyTable($table, $columns, $slugColumn);
        
        if ($options['dry-run']) {
            $this->logSection('candle', 'Dry run - rollback');
            $this->connection->rollback();
        }
        else {
            $this->logSection('candle', 'Commiting');
            $this->connection->commit();
        }
        
    }
    catch (Exception $e) {
        $this->logSection('candle', 'Exception occured, executing rollback');
        $this->connection->rollback();
        $this->logBlock(array($e->getMessage(), $e->getTraceAsString()), 'ERROR');
    }

    $this->log('Done');
  }

}