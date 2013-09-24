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


class CandleImporter
{

  /**
   *
   * @var PDO
   */
  protected $connection;

  protected $warningHandler;

  public function warning($message) {
    if ($this->warningHandler !== null) {
      $this->warningHandler->warning($message);
    }
  }

  public function deleteAllData() {
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

  public function debugDumpTables() {
      $tables = array('tmp_insert_lesson_type', 'tmp_insert_room_type',
          'tmp_insert_teacher', 'tmp_insert_room', 'tmp_insert_subject',
          'tmp_insert_lesson', 'tmp_insert_lesson_teacher',
          'tmp_insert_lesson_student_group', 'tmp_insert_lesson_link');

      foreach ($tables as $table) {
          $this->connection->exec('DROP TABLE IF EXISTS debug_'.$table);
          $this->connection->exec('CREATE TABLE debug_'.$table.' SELECT * FROM '.$table);
      }
  }

  public function mergeLessonTypes() {
      $sql = 'UPDATE lesson_type t, tmp_insert_lesson_type i';
      $sql .= ' SET t.name=i.name WHERE t.code = i.code';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO lesson_type (name, code) SELECT name, code';
      $sql .= ' FROM tmp_insert_lesson_type i';
      $sql .= ' WHERE i.code NOT IN (SELECT code FROM lesson_type)';
      $this->connection->exec($sql);
  }

  public function mergeRoomTypes() {
      $sql = 'UPDATE room_type r, tmp_insert_room_type i';
      $sql .= ' SET r.name=i.name WHERE r.code = i.code';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO room_type (name, code) SELECT name, code';
      $sql .= ' FROM tmp_insert_room_type i';
      $sql .= ' WHERE i.code NOT IN (SELECT code FROM room_type)';
      $this->connection->exec($sql);
  }

  public function mergeTeachers() {
      $sql = 'UPDATE teacher t, tmp_insert_teacher i';
      $sql .= ' SET t.given_name=i.given_name, ';
      $sql .= ' t.family_name = i.family_name, ';
      $sql .= ' t.iniciala = i.iniciala, ';
      $sql .= ' t.oddelenie = i.oddelenie, ';
      $sql .= ' t.katedra = i.katedra, ';
      $sql .= ' t.login = i.login';
      $sql .= ' WHERE t.external_id = i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO teacher (given_name, family_name, ';
      $sql .= ' iniciala, oddelenie, katedra, external_id, login) ';
      $sql .= ' SELECT given_name, family_name, iniciala, oddelenie, ';
      $sql .= ' katedra, external_id, login';
      $sql .= ' FROM tmp_insert_teacher i';
      $sql .= ' WHERE i.external_id NOT IN (SELECT external_id FROM teacher)';
      $this->connection->exec($sql);
  }

  public function mergeRooms() {
      $sql = 'UPDATE room r, tmp_insert_room i, room_type rt';
      $sql .= ' SET r.room_type_id=rt.id, ';
      $sql .= ' r.capacity = i.capacity ';
      $sql .= ' WHERE r.name = i.name AND rt.code = i.room_type';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO room (name, room_type_id, ';
      $sql .= ' capacity) ';
      $sql .= 'SELECT i.name, rt.id, i.capacity';
      $sql .= ' FROM tmp_insert_room i, room_type rt';
      $sql .= ' WHERE rt.code=i.room_type AND i.name NOT IN (SELECT name FROM room)';
      $this->connection->exec($sql);
  }

  public function mergeSubjects() {
      $sql = 'UPDATE subject s, tmp_insert_subject i';
      $sql .= ' SET s.name=i.name, s.code=i.code, s.short_code=i.short_code, ';
      $sql .= ' s.credit_value=i.credit_value, s.rozsah=i.rozsah';
      $sql .= ' WHERE s.external_id=i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO subject (name, code, short_code, ';
      $sql .= ' credit_value, rozsah, external_id) ';
      $sql .= 'SELECT i.name, i.code, i.short_code, i.credit_value, i.rozsah, ';
      $sql .= ' i.external_id';
      $sql .= ' FROM tmp_insert_subject i';
      $sql .= ' WHERE i.external_id NOT IN (SELECT external_id FROM subject)';
      $this->connection->exec($sql);
  }

  public function mergeLessons() {
      $sql = 'UPDATE lesson l, tmp_insert_lesson i, lesson_type lt, room r, subject s';
      $sql .= ' SET l.day=i.day, ';
      $sql .= ' l.start=i.start, l.end=i.end, ';
      $sql .= ' l.lesson_type_id=lt.id, l.room_id=r.id, l.subject_id=s.id, ';
      $sql .= ' l.note=i.note ';
      $sql .= ' WHERE lt.code = i.lesson_type AND r.name = i.room ';
      $sql .= ' AND s.external_id=i.subject AND l.external_id=i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO lesson (day, start, end, lesson_type_id, room_id, ';
      $sql .= ' subject_id, external_id, note) ';
      $sql .= 'SELECT i.day, i.start, i.end, lt.id, r.id, s.id, i.external_id, ';
      $sql .= ' i.note ';
      $sql .= ' FROM tmp_insert_lesson i, lesson_type lt, room r, subject s';
      $sql .= ' WHERE lt.code = i.lesson_type AND r.name = i.room ';
      $sql .= ' AND s.external_id=i.subject ';
      $sql .= ' AND i.external_id NOT IN (SELECT external_id FROM lesson)';
      $this->connection->exec($sql);

      $sql = 'DELETE FROM tl USING teacher_lessons tl, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE tl.lesson_id=l.id AND l.external_id=i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO teacher_lessons (teacher_id, lesson_id) ' ;
      $sql .= ' SELECT t.id, l.id ';
      $sql .= ' FROM lesson l, teacher t, tmp_insert_lesson_teacher i';
      $sql .= ' WHERE l.external_id=i.lesson_external_id AND t.external_id=i.teacher_external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO student_group (name)';
      $sql .= ' SELECT DISTINCT i.student_group';
      $sql .= ' FROM tmp_insert_lesson_student_group i';
      $sql .= ' WHERE i.student_group NOT IN (SELECT name from student_group)';
      $this->connection->exec($sql);

      $sql = 'DELETE FROM sgl';
      $sql .= ' USING student_group_lessons sgl, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE sgl.lesson_id=l.id ';
      $sql .= ' AND l.external_id=i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO student_group_lessons (student_group_id, lesson_id)';
      $sql .= ' SELECT sg.id, l.id';
      $sql .= ' FROM student_group sg, lesson l, tmp_insert_lesson_student_group i';
      $sql .= ' WHERE sg.name=i.student_group AND l.external_id=i.lesson_external_id';
      $this->connection->exec($sql);

      $sql = 'DELETE FROM ll';
      $sql .= ' USING linked_lessons ll, lesson l, tmp_insert_lesson i';
      $sql .= ' WHERE (ll.lesson1_id=l.id OR ll.lesson2_id=l.id)';
      $sql .= ' AND l.external_id=i.external_id';
      $this->connection->exec($sql);

      $sql = 'INSERT INTO linked_lessons (lesson1_id, lesson2_id)';
      $sql .= ' SELECT l1.id, l2.id';
      $sql .= ' FROM lesson l1, lesson l2, tmp_insert_lesson_link i';
      $sql .= ' WHERE l1.external_id=i.lesson1_external_id ';
      $sql .= ' AND l2.external_id=i.lesson2_external_id';
      $this->connection->exec($sql);

  }

  public function deleteExcessSubjects() {
      $sql = 'DELETE FROM s';
      $sql .= ' USING subject s';
      $sql .= ' WHERE s.external_id NOT IN (SELECT s.external_id FROM tmp_insert_subject s)';
      $this->connection->exec($sql);
  }

  public function deleteExcessLessons() {
      // TODO: mozno nemazat, ale len pridat flag vymazane, alebo nejaky zaznam do historie

      $sql = 'DELETE FROM l';
      $sql .= ' USING lesson l';
      $sql .= ' WHERE l.external_id NOT IN (SELECT i.external_id FROM tmp_insert_lesson i)';
      $this->connection->exec($sql);
  }

  public function checkVersion(array $version) {
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

      $import_version = date('Y-m-d H:i:s', $version['date']);
      if ($import_version <= $max_version) {
          $this->warning('You are probably trying to import version older than already stored in database! ' . 
                  "Latest version is $max_version, you are importing $import_version");
      }
  }

  public function insertDataUpdate($description, array $version) {
      $sql = 'INSERT INTO data_update';
      $sql .= ' (datetime, description, version, semester, school_year_low)';
      $sql .= ' VALUES (NOW(), ?, ?, ?, ?)';

      $pos = strpos($version['skolrok'], '/');
      if ($pos === false) {
          $skolrok = intval($version['skolrok']);
      }
      else {
          $skolrok = intval(substr($version['skolrok'], 0, $pos));
      }

      $prepared = $this->connection->prepare($sql);
      $data = array($description,
          date('Y-m-d H:i:s', $version['date']),
          $version['semester'],
          $skolrok);
      $prepared->execute($data);

  }

  public function __construct(PDO $connection, $warningHandler = null) {
      $this->connection = $connection;
      $this->warningHandler = $warningHandler;
  }

}
