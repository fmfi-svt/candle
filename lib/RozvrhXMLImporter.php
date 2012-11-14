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


class RozvrhXMLImporter /* implements RozvrhXMLConsumer*/
{

  /** @var PDO */
  protected $connection;

  /** @var array(PDOStatement) */
  protected $insert;

  protected $rozvrhData;
  
  /**
   * 
   * @return array(xml-elem-type => array(
   *    'table-name' => table name, 
   *    'columns' => array(
   *        column name => sql column type,
   *    ),
   *    'primary-key' => array(column names),
   *  )
   */
  protected function getTableDefinitions() {
      return array(
          'typ-hodiny' => array(
              'table-name' => 'tmp_insert_lesson_type',
              'columns' => array(
                  'name' => 'varchar(30) not null',
                  'code' => 'varchar(1) not null',
              ),
              'primary-key' => array('code'),
           ),
          'typ-miestnosti' => array(
              'table-name' => 'tmp_insert_room_type',
              'columns' => array(
                  'name' => 'varchar(30) not null',
                  'code' => 'varchar(1) not null',
              ),
              'primary-key' => array('code'),
           ),
          'ucitel' => array(
              'table-name' => 'tmp_insert_teacher',
              'columns' => array(
                  'given_name' => 'varchar(50)',
                  'family_name' => 'varchar(50) not null',
                  'iniciala' => 'varchar(50)',
                  'oddelenie' => 'varchar(50)',
                  'katedra' => 'varchar(50)',
                  'external_id' => 'varchar(50) binary not null collate utf8_bin',
                  'login' => 'varchar(50)',
              ),
              'primary-key' => array('external_id'),
          ),
          'miestnost' => array(
              'table-name' => 'tmp_insert_room',
              'columns' => array(
                  'name' => 'varchar(30) not null',
                  'room_type' => 'varchar(1) not null',
                  'capacity' => 'integer not null',
              ),
              'primary-key' => array('name'),
          ),
          'predmet' => array(
              'table-name' => 'tmp_insert_subject',
              'columns' => array(
                  'name' => 'varchar(100)',
                  'code' => 'varchar(50)',
                  'short_code' => 'varchar(20)',
                  'credit_value' => 'integer not null',
                  'rozsah' => 'varchar(30)',
                  'external_id' => 'varchar(50) binary not null collate utf8_bin',
              ),
              'primary-key' => array('external_id'),
          ),
          'hodina' => array(
              'table-name' => 'tmp_insert_lesson',
              'columns' => array(
                  'day' => 'integer not null',
                  'start' => 'integer not null',
                  'end' => 'integer not null',
                  'lesson_type' => 'varchar(1) not null',
                  'room' => 'varchar(30) not null',
                  'subject' => 'varchar(50) not null',
                  'external_id' => 'integer not null',
                  'note' => 'varchar(240)',
              ),
              'primary-key' => array('external_id'),
          ),
          'hodina-ucitel' => array(
              'table-name' => 'tmp_insert_lesson_teacher',
              'columns' => array(
                  'lesson_external_id' => 'integer not null',
                  'teacher_external_id' => 'varchar(50) binary not null collate utf8_bin',
              ),
          ),
          'hodina-kruzok' => array(
              'table-name' => 'tmp_insert_lesson_student_group',
              'columns' => array(
                  'lesson_external_id' => 'integer not null',
                  'student_group' => 'varchar(30) not null',
              )
          ),
          'hodina-hodina' => array(
              'table-name' => 'tmp_insert_lesson_link',
              'columns' => array(
                  'lesson1_external_id' => 'integer not null',
                  'lesson2_external_id' => 'integer not null',
              )
          ),
      );
  }
  
  public function consumeRozvrh(array $location, array $rozvrh) {
      $this->rozvrhData = $rozvrh;
  }

  protected function convertTypHodiny(array $location, array $typHodiny) {
      return array('name' => $typHodiny['popis'], 'code' => $typHodiny['id']);
  }
  
  public function consumeTypHodiny(array $location, array $typHodiny) {
    $this->insert['typ-hodiny']->execute($this->convertTypHodiny($location, $typHodiny));
  }

  protected function convertTypMiestnosti(array $location, array $typMiestnosti) {
      return array('name' => $typMiestnosti['popis'], 'code' => $typMiestnosti['id']);
  }
  
  public function consumeTypMiestnosti(array $location, array $typMiestnosti) {
    $this->insert['typ-miestnosti']->execute($this->convertTypMiestnosti($location, $typMiestnosti));
  }

  protected function convertUcitel(array $location, array $ucitel) {
      if (isset($ucitel['login'])) {
        $login = trim($ucitel['login']);
      }
      else {
        $login = null;
      }
      return array(
          'given_name' => trim($ucitel['meno']),
          'family_name' => trim($ucitel['priezviko']),
          'iniciala' => trim($ucitel['iniciala']),
          'oddelenie' => trim($ucitel['oddelenie']),
          'katedra' => trim($ucitel['katedra']),
          'external_id' => $this->mangleExtId($ucitel['id']),
          'login' => $login,
      );
  }
  
  public function consumeUcitel(array $location, array $ucitel) {
    $this->insert['ucitel']->execute($this->convertUcitel($location, $ucitel));
  }

  protected function convertMiestnost(array $location, array $miestnost) {
      return array(
          'name' => $miestnost['nazov'],
          'room_type' => $miestnost['typ'],
          'capacity' => (int) $miestnost['kapacita'],
      );
  }
  
  public function consumeMiestnost(array $location, array $miestnost) {
    $this->insert['miestnost']->execute($this->convertMiestnost($location, $miestnost));
  }

  protected function convertPredmet(array $location, array $predmet) {
    return array(
        'name' => $predmet['nazov'],
        'code' => $predmet['kod'],
        'short_code' => $predmet['kratkykod'],
        'credit_value' => (int) $predmet['kredity'],
        'rozsah' => $predmet['rozsah'],
        'external_id' => $predmet['id'],
    );
  }
  
  public function consumePredmet(array $location, array $predmet) {
    $this->insert['predmet']->execute($this->convertPredmet($location, $predmet));
  }

  protected function convertHodina(array $location, array $hodina) {
    $lessonId = (int) $hodina['oldid'];
    if (isset($hodina['poznamka'])) {
        $note = $hodina['poznamka'];
    }
    else {
        $note = null;
    }
    
    return array(
        'day' => $this->dayFromCode($hodina['den']),
        'start' => (int) $hodina['zaciatok'],
        'end' => (int) $hodina['koniec'],
        'lesson_type' => $hodina['typ'],
        'room' => $hodina['miestnost'],
        'subject' => $hodina['predmet'],
        'external_id' => $lessonId,
        'note' => $note,
    );
  }
  
  protected function convertHodinaUcitel(array $location, array $convertedHodina,
          $ucitelId) {
      return array(
          'lesson_external_id' => $convertedHodina['external_id'],
          'teacher_external_id' => $this->mangleExtId($ucitelId),
      );
  }
  
  protected function convertHodinaKruzok(array $location, array $convertedHodina,
          $kruzok) {
      return array(
          'lesson_external_id' => $convertedHodina['external_id'],
          'student_group' => $kruzok,
      );
  }
  
  protected function convertHodinaHodina(array $location, array $convertedHodina,
          $zviazanaHodina) {
      return array(
          'lesson1_external_id' => $convertedHodina['external_id'],
          'lesson2_external_id' => (int) $zviazanaHodina,
      );
  }
  
  public function consumeHodina(array $location, array $hodina) {
    $convertedHodina = $this->convertHodina($location, $hodina);
    $this->insert['hodina']->execute($convertedHodina);
    
    if (isset($hodina['ucitelia'])) {
        $ucitelia = explode(',', $hodina['ucitelia']);
        foreach ($ucitelia as $ucitelId) {
            $this->insert['hodina-ucitel']->execute($this->convertHodinaUcitel($location,
                    $convertedHodina, $ucitelId));
        }
    }

    if (isset($hodina['kruzky'])) {
        $kruzky = explode(',', $hodina['kruzky']);
        foreach ($kruzky as $kruzok) {
            $this->insert['hodina-kruzok']->execute($this->convertHodinaKruzok($location,
                    $convertedHodina, $kruzok));
        }
    }

    if (isset($hodina['zviazaneoldid'])) {
        $zviazaneHodiny = explode(',', $hodina['zviazaneoldid']);
        foreach ($zviazaneHodiny as $zviazanaHodina) {
            $this->insert['hodina-hodina']->execute($this->convertHodinaHodina($location,
                    $convertedHodina, $zviazanaHodina));
        }
    }
  }

  protected function createTemporaryTables() {
      $tables = $this->getTableDefinitions();
      foreach ($tables as $key => $table) {
          $sql = "CREATE TEMPORARY TABLE ";
          if (!isset($table['table-name'])) {
              throw new LogicException('Table name must be defined for ' . $key);
          }
          $sql .= $table['table-name'];
          $sql .= ' (';
          if (!isset($table['columns'])) {
              throw new LogicException('Columns must be defined for ' . $key);
          }
          $first = true;
          foreach ($table['columns'] as $name => $type) {
              if (!$first) $sql .= ', ';
              $first = false;
              $sql .= $name . ' ' . $type;
          }
          $sql .= ')';
          $this->connection->exec($sql);
      }
  }

  protected function prepareInsertStatements() {
      $tables = $this->getTableDefinitions();
      $this->insert = array();
      foreach ($tables as $key => $table) {
          $sql = 'INSERT INTO ' . $table['table-name'];
          $sql .= ' (' . join(', ', array_keys($table['columns']));
          $sql .= ') VALUES (';
          $first = true;
          foreach ($table['columns'] as $name => $type) {
              if (!$first) $sql .= ', ';
              $first = false;
              $sql .= ':' . $name;
          }
          $sql .= ')';
          $this->insert[$key] = $this->connection->prepare($sql);
      }
  }

  protected function createPrimaryKeys() {
      $tables = $this->getTableDefinitions();
      
      foreach ($tables as $table) {
          if (isset($table['primary-key'])) {
              $sql = 'ALTER TABLE ';
              $sql .= $table['table-name'];
              $sql .= ' ADD PRIMARY KEY (';
              $sql .= join(', ', $table['primary-key']);
              $sql .= ')';
              $this->connection->exec($sql);
          }
      }
  }

  protected function mangleExtId($id) {
      return substr(sha1($id),0,30);
  }

  public function __construct(PDO $connection)
  {
      $this->connection = $connection;
      $this->rozvrhData = null;
  }

  /**
   * Initialize the database to receive the data
   *
   * This method should be called outside of transaction context,
   * as it causes implicit commit within mysql. (as it uses DDL statements)
   */
  public function prepareDatabase()
  {
      $this->createTemporaryTables();
      $this->createPrimaryKeys();
  }

  /**
   * Prepare transaction to insert the data.
   *
   * This method should be called from within transaction context,
   * as it initializes prepared statements.
   */
  public function prepareTransaction()
  {
      $this->prepareInsertStatements();
  }

  public function getVersion() {
      return array(
          'date' => $this->rozvrhData['verzia'],
          'skolrok' => $this->rozvrhData['skolrok'],
          'semester' => $this->rozvrhData['semester'],
      );
  }
  
  protected function dayFromCode($code) {
    $days = array('pon'=>0, 'uto'=>1, 'str'=>2, 'stv'=>3, 'pia'=>4);
    return $days[$code];
  }

}
