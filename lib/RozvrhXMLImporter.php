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

  /** @var PDOStatement */
  protected $insertLessonType;

  /** @var PDOStatement */
  protected $insertRoomType;

  /** @var PDOStatement */
  protected $insertTeacher;

  /** @var PDOStatement */
  protected $insertRoom;

  /** @var PDOStatement */
  protected $insertLesson;

  /** @var PDOStatement */
  protected $insertLessonTeacher;

  /** @var PDOStatement */
  protected $insertLessonStudentGroup;

  /** @var PDOStatement */
  protected $insertLessonLink;

  /** @var PDOStatement */
  protected $insertSubject;

  /** @var int Unix time */
  protected $version;

  /** @var string */
  protected $skolrok;

  /** @var string */
  protected $semester;

  public function consumeRozvrh(array $location, array $rozvrh) {
      $this->version = $rozvrh['verzia'];
      $this->skolrok = $rozvrh['skolrok'];
      $this->semester = $rozvrh['semester'];
  }

  public function consumeTypHodiny(array $location, array $typHodiny) {
    $this->insertLessonType->execute(array(
        $typHodiny['popis'], $typHodiny['id']
    ));
  }

  public function consumeTypMiestnosti(array $location, array $typMiestnosti) {
    $this->insertRoomType->execute(array(
        $typMiestnosti['popis'], $typMiestnosti['id']
    ));
  }

  public function consumeUcitel(array $location, array $ucitel) {
    if (isset($ucitel['login'])) {
        $login = trim($ucitel['login']);
    }
    else {
        $login = null;
    }
    $this->insertTeacher->execute(array(
        trim($ucitel['meno']), trim($ucitel['priezvisko']),
        trim($ucitel['iniciala']), trim($ucitel['oddelenie']),
        trim($ucitel['katedra']), $this->mangleExtId($ucitel['id']),
        $login
    ));
  }

  public function consumeMiestnost(array $location, array $miestnost) {
    $this->insertRoom->execute(array(
        $miestnost['nazov'], $miestnost['typ'],
        (int) $miestnost['kapacita']
    ));
  }

  public function consumePredmet(array $location, array $predmet) {
    $this->insertSubject->execute(array(
        $predmet['nazov'], $predmet['kod'],
        $myShortCode, (int) $predmet['kredity'],
        $predmet['rozsah'], $predmet['id']
    ));
  }

  public function consumeHodina(array $location, array $hodina) {
    $lessonId = (int) $hodina['oldid'];
    if (isset($hodina['poznamka'])) {
        $note = $hodina['poznamka'];
    }
    else {
        $note = null;
    }

    $this->insertLesson->execute(array(
        Candle::dayFromCode($hodina['den']), (int) $hodina['zaciatok'],
        (int) $hodina['koniec'], $hodina['typ'],
        $hodina['miestnost'], $hodina['predmet'],
        $lessonId, $note
    ));

    if (isset($hodina['ucitelia'])) {
        $ucitelia = explode(',', $hodina['ucitelia']);
        foreach ($ucitelia as $ucitelId) {
            $this->insertLessonTeacher->execute(array($lessonId, $this->mangleExtId($ucitelId)));
        }
    }

    if (isset($hodina['kruzky'])) {
        $kruzky = explode(',', $hodina['kruzky']);
        foreach ($kruzky as $kruzok) {
            $this->insertLessonStudentGroup->execute(array($lessonId, $kruzok));
        }
    }

    if (isset($hodina['zviazaneoldid'])) {
        $zviazaneHodiny = explode(',', $hodina['zviazaneoldid']);
        foreach ($zviazaneHodiny as $zviazanaHodina) {
            $this->insertLessonLink->execute(array($lessonId, $zviazanaHodina));
        }
    }
  }

  protected function createTemporaryTables() {
      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_type ";
      $sql .= "(name varchar(30) not null, code varchar(1) not null)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_room_type ";
      $sql .= "(name varchar(30) not null, code varchar(1) not null)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_teacher ";
      $sql .= "(given_name varchar(50), family_name varchar(50) not null,";
      $sql .= "iniciala varchar(50), oddelenie varchar(50), katedra varchar(50),";
      $sql .= "external_id varchar(50) binary not null collate utf8_bin, ";
      $sql .= "login varchar(50))";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_room ";
      $sql .= "(name varchar(30) not null, room_type varchar(1) not null, capacity integer not null)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_subject ";
      $sql .= "(name varchar(100), code varchar(50), short_code varchar(20), ";
      $sql .= "credit_value integer not null, rozsah varchar(30), external_id varchar(30) binary not null collate utf8_bin)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson ";
      $sql .= "(day integer not null, start integer not null, end integer not null, ";
      $sql .= "lesson_type varchar(1) not null, room varchar(30) not null, ";
      $sql .= "subject varchar(30) not null, external_id integer not null, ";
      $sql .= "note varchar(240))";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_teacher ";
      $sql .= "(lesson_external_id integer not null, teacher_external_id varchar(50) binary not null collate utf8_bin)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_student_group ";
      $sql .= "(lesson_external_id integer not null, student_group varchar(30) not null)";
      $res = $this->connection->exec($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_link ";
      $sql .= "(lesson1_external_id integer not null, lesson2_external_id integer not null)";
      $res = $this->connection->exec($sql);
  }

  protected function prepareInsertStatements() {
      $sql = 'INSERT INTO tmp_insert_lesson_type (name, code) VALUES (?, ?)';
      $this->insertLessonType = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_room_type (name, code) VALUES (?, ?)';
      $this->insertRoomType = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_teacher (given_name, family_name, iniciala, ';
      $sql .= 'oddelenie, katedra, external_id, login) VALUES (?, ?, ?, ?, ?, ?, ?)';
      $this->insertTeacher = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_room (name, room_type, capacity';
      $sql .= ') VALUES (?, ?, ?)';
      $this->insertRoom = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_subject (name, code, short_code, ';
      $sql .= 'credit_value, rozsah, external_id) VALUES (?, ?, ?, ?, ?, ?)';
      $this->insertSubject = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_lesson (day, start, end, lesson_type, ';
      $sql .= 'room, subject, external_id, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
      $this->insertLesson = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_lesson_teacher (lesson_external_id, ';
      $sql .= 'teacher_external_id) VALUES (?, ?)';
      $this->insertLessonTeacher = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_lesson_student_group (lesson_external_id, ';
      $sql .= 'student_group) VALUES (?, ?)';
      $this->insertLessonStudentGroup = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_lesson_link (lesson1_external_id, ';
      $sql .= 'lesson2_external_id) VALUES (?, ?)';
      $this->insertLessonLink = $this->connection->prepare($sql);
  }

  protected function createPrimaryKeys() {
      $sql = 'ALTER TABLE tmp_insert_lesson_type';
      $sql .= ' ADD PRIMARY KEY (code)';
      $this->connection->exec($sql);

      $sql = 'ALTER TABLE tmp_insert_room_type';
      $sql .= ' ADD PRIMARY KEY (code)';
      $this->connection->exec($sql);

      $sql = 'ALTER TABLE tmp_insert_teacher';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->connection->exec($sql);

      $sql = 'ALTER TABLE tmp_insert_room';
      $sql .= ' ADD PRIMARY KEY (name)';
      $this->connection->exec($sql);

      $sql = 'ALTER TABLE tmp_insert_subject';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->connection->exec($sql);

      $sql = 'ALTER TABLE tmp_insert_lesson';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->connection->exec($sql);
  }

  protected function mangleExtId($id) {
      return substr(sha1($id),0,30);
  }

  public function __construct(PDO $connection)
  {
      $this->connection = $connection;
      $this->version = null;
      $this->skolrok = null;
      $this->semester = null;
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
          'date' => $this->version,
          'skolrok' => $this->skolrok,
          'semester' => $this->semester,
      );
  }

}
