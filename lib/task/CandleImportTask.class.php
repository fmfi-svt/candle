<?php

class CandleImportTask extends sfBaseTask
{

  protected static $ucitelFields = array('priezvisko', 'meno', 'iniciala', 'katedra', 'oddelenie');
  protected static $miestnostFields = array('nazov', 'kapacita', 'typ');
  protected static $predmetFields = array('nazov', 'kod', 'kratkykod', 'kredity', 'rozsah');
  protected static $hodinaFields = array('den', 'zaciatok', 'koniec', 'miestnost', 'trvanie', 'predmet', 'ucitelia', 'kruzky', 'typ', 'zviazanehodiny', 'oldid');

  const STATE_ROOT = 0;
  const STATE_TYPY = 1;
  const STATE_TYPYMIESTNOSTI = 2;
  const STATE_UCITELIA = 3;
  const STATE_MIESTNOSTI = 4;
  const STATE_PREDMETY = 5;
  const STATE_HODINY = 6;

  /**
   *
   * @var Doctrine_Connection
   */
  protected $connection;

  /**
   *
   * @var Doctrine_Connection_Statement
   */
  protected $insertLessonType;

  /**
   *
   * @var Doctrine_Connection_Statement
   */
  protected $insertRoomType;

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
        new sfCommandOption('ignore-errors', null, sfCommandOption::PARAMETER_NONE, 'Ignore any errors (it is advisable to also use --dry-run)'),
        new sfCommandOption('print-sql', null, sfCommandOption::PARAMETER_NONE, 'Print SQL commands executed'),
        new sfCommandOption('debug-dump-tables', null, sfCommandOption::PARAMETER_NONE, 'Create a persistent copy of temporary tables'),
        new sfCommandOption('warnings-as-errors', null, sfCommandOption::PARAMETER_NONE, 'Treat warnings as errors'),
        new sfCommandOption('no-removes', null, sfCommandOption::PARAMETER_NONE, 'Don\'t remove objects in db that were deleted from xml'),
        new sfCommandOption('message', null, sfCommandOption::PARAMETER_OPTIONAL, 'Description of the update', ''),
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
      return $this->connection->execute($sql);
  }

  protected function executePreparedSQL(Doctrine_Connection_Statement $prepared, array $params) {
      $prepared->getQuery();
      return $prepared->execute($params);
  }

  protected function createParseErrorMessage($parser, $description) {
      $message = 'Line '.xml_get_current_line_number($parser);
      $message .= ', Column '.xml_get_current_column_number($parser);
      $message .= ' ('.implode('.', $this->currentElementPath).')';
      $message .= ': '.$description;
      return $message;
  }

  protected function rethrowParseError($parser, Exception $exception) {
      $message = 'Error: '.$this->createParseErrorMessage($parser, $exception->getMessage());
      if ($this->ignoreErrors) {
          $this->logBlock($message, 'ERROR');
      }
      else {
        throw new Exception($message, null, $exception);
      }
  }

  protected function parseError($parser, $description) {
      $message = 'Error: '.$this->createParseErrorMessage($parser, $description);
      if ($this->ignoreErrors) {
          $this->logBlock($message);
      }
      else {
          throw new Exception($message);
      }
  }

  protected function parseWarning($parser, $description) {
      $message = 'Warning: '.$this->createParseErrorMessage($parser, $description);
      if (!$this->warningsAsErrors) {
          $this->logBlock($message, 'INFO');
      }
      else {
          throw new Exception($message);
      }
  }

  protected function parseErrorUnexpectedElement($parser, $name) {
      $this->parseError($parser, 'Unexpected element: '.$name);
  }

  protected function setActiveField($parser, $name) {
      if ($this->dataField !== null) {
          $this->parseErrorUnexpectedElement($parser, $name);
      }
      if (isset($this->elementData[$name])) {
          $this->parseError($parser, 'Value for '.$name.' field already set');
      }
      $this->dataField = $name;
  }

  protected function parser_startElement($parser, $name, $attrs) {
      //echo '<'.$name.' '.$this->state."\n";
      $this->currentElementPath[] = $name;
      if ($this->state == self::STATE_ROOT) {
          if ($name == 'rozvrh') {
              // skip
          }
          else if ($name == 'typy') {
              $this->state = self::STATE_TYPY;
          }
          else if ($name == 'typymiestnosti') {
              $this->state = self::STATE_TYPYMIESTNOSTI;
          }
          else if ($name == 'ucitelia') {
              $this->state = self::STATE_UCITELIA;
          }
          else if ($name == 'miestnosti') {
              $this->state = self::STATE_MIESTNOSTI;
          }
          else if ($name == 'predmety') {
              $this->state = self::STATE_PREDMETY;
          }
          else if ($name == 'hodiny') {
              $this->state = self::STATE_HODINY;
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_TYPY) {
          if ($name == 'typ') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->elementData['popis'] = $attrs['popis'];
              try {
                $this->handleTyp();
              }
              catch (Exception $e) {
                $this->rethrowParseError($parser, $e);
              }
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_TYPYMIESTNOSTI) {
          if ($name == 'typmiestnosti') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->elementData['popis'] = $attrs['popis'];
              try {
                $this->handleTypMiestnosti();
              }
              catch (Exception $e) {
                $this->rethrowParseError($parser, $e);
              }
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_UCITELIA) {
          if ($name == 'ucitel') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->dataFields = self::$ucitelFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_MIESTNOSTI) {
          if ($name == 'miestnost') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->dataFields = self::$miestnostFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_PREDMETY) {
          if ($name == 'predmet') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id']=$attrs['id'];
              $this->dataFields = self::$predmetFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_HODINY) {
          if ($name == 'hodina') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id']=$attrs['id'];
              $this->dataFields = self::$predmetFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else {
          $this->parseError($parser, 'Some internal state messed, '.$this->state);
      }
  }

  protected function parser_endElement($parser, $name) {
      //echo '</'.$name."\n";
      $this->dataField = null;
      if ($this->state == self::STATE_TYPY) {
          if ($name == 'typy') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'typ') {
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_TYPYMIESTNOSTI) {
          if ($name == 'typymiestnosti') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'typmiestnosti') {
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_UCITELIA) {
          if ($name == 'ucitelia') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'ucitel') {
              $this->dataFields = null;
              try {
                $this->handleUcitel($parser);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_MIESTNOSTI) {
          if ($name == 'miestnosti') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'miestnost') {
              $this->dataFields = null;
              try {
                $this->handleMiestnost($parser);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_PREDMETY) {
          if ($name == 'predmety') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'predmet') {
              $this->dataFields = null;
              try {
                $this->handlePredmet($parser);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_HODINY) {
          if ($name == 'hodiny') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'hodina') {
              $this->dataFields = null;
              try {
                $this->handleHodina($parser);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      // Remove last path element
      array_splice($this->currentElementPath, -1);
  }

  protected function parser_characterData($parser, $data) {
      if ($this->dataField == null) return;
      if (!isset($this->elementData[$this->dataField])) {
          $this->elementData[$this->dataField] = $data;
      }
      else {
          $this->elementData[$this->dataField] .= $data;
      }
  }

  protected function handleTyp($parser) {
    //print_r($this->elementData);
    $this->insertLessonType->execute(array(
        $this->elementData['popis'], $this->elementData['id']
    ));
  }

  protected function handleTypMiestnosti($parser) {
    //print_r($this->elementData);
    $this->insertRoomType->execute(array(
        $this->elementData['popis'], $this->elementData['id']
    ));
  }

  protected function handleUcitel($parser) {
    //print_r($this->elementData);
    $this->insertTeacher->execute(array(
        trim($this->elementData['meno']), trim($this->elementData['priezvisko']),
        trim($this->elementData['iniciala']), trim($this->elementData['oddelenie']),
        trim($this->elementData['katedra']), $this->elementData['id']
    ));
  }

  protected function handleMiestnost($parser) {
    //print_r($this->elementData);
    $this->insertRoom->execute(array(
        $this->elementData['nazov'], $this->elementData['typ'],
        (int) $this->elementData['kapacita']
    ));
  }

  protected function handlePredmet($parser) {
    //print_r($this->elementData);
    $myShortCode = Candle::subjectShortCodeFromLongCode($this->elementData['kod']);

    if ($myShortCode !== false && $myShortCode != $this->elementData['kratkykod']) {
        $this->parseWarning($parser, 
                'Short code does not have expected value, got \''.
                $this->elementData['kratkykod'].'\', expected \''.$myShortCode.'\'');
    }

    $this->insertSubject->execute(array(
        $this->elementData['nazov'], $this->elementData['kod'],
        $myShortCode, (int) $this->elementData['kredity'],
        $this->elementData['rozsah'], $this->elementData['id']
    ));
  }

  protected function handleHodina($parser) {
    //print_r($this->elementData);
    $lessonId = (int) $this->elementData['oldid'];

    $this->insertLesson->execute(array(
        Candle::dayFromCode($this->elementData['den']), (int) $this->elementData['zaciatok'],
        (int) $this->elementData['koniec'], $this->elementData['typ'],
        $this->elementData['miestnost'], $this->elementData['predmet'],
        $lessonId
    ));

    if (isset($this->elementData['ucitelia'])) {
        $ucitelia = explode(',', $this->elementData['ucitelia']);
        foreach ($ucitelia as $ucitelId) {
            $this->insertLessonTeacher->execute(array($lessonId, $ucitelId));
        }
    }

    if (isset($this->elementData['kruzky'])) {
        $kruzky = explode(',', $this->elementData['kruzky']);
        foreach ($kruzky as $kruzok) {
            $this->insertLessonStudentGroup->execute(array($lessonId, $kruzok));
        }
    }

    if (isset($this->elementData['zviazanehodiny'])) {
        $zviazaneHodiny = explode(',', $this->elementData['zviazanehodiny']);
        foreach ($zviazaneHodiny as $zviazanaHodina) {
            $this->insertLessonLink->execute(array($lessonId, $zviazanaHodina));
        }
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

  protected function createTemporaryTables() {
      $this->logSection('candle', 'Creating temporary tables');

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_type ";
      $sql .= "(name varchar(30) not null, code varchar(1) not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_room_type ";
      $sql .= "(name varchar(30) not null, code varchar(1) not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_teacher ";
      $sql .= "(given_name varchar(50), family_name varchar(50) not null,";
      $sql .= "iniciala varchar(50), oddelenie varchar(50), katedra varchar(50),";
      $sql .= "external_id varchar(50) binary not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_room ";
      $sql .= "(name varchar(30) not null, room_type varchar(1) not null, capacity integer not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_subject ";
      $sql .= "(name varchar(100), code varchar(50), short_code varchar(20), ";
      $sql .= "credit_value integer not null, rozsah varchar(30), external_id varchar(30) binary not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson ";
      $sql .= "(day integer not null, start integer not null, end integer not null, ";
      $sql .= "lesson_type varchar(1) not null, room varchar(30) not null, ";
      $sql .= "subject varchar(30) not null, external_id integer not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_teacher ";
      $sql .= "(lesson_external_id integer not null, teacher_external_id varchar(50) binary not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_student_group ";
      $sql .= "(lesson_external_id integer not null, student_group varchar(30) not null)";
      $res = $this->executeSQL($sql);

      $sql = "CREATE TEMPORARY TABLE tmp_insert_lesson_link ";
      $sql .= "(lesson1_external_id integer not null, lesson2_external_id integer not null)";
      $res = $this->executeSQL($sql);
  }

  protected function prepareInsertStatements() {
      $this->logSection('candle', 'Creating prepared statements');

      $sql = 'INSERT INTO tmp_insert_lesson_type (name, code) VALUES (?, ?)';
      $this->insertLessonType = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_room_type (name, code) VALUES (?, ?)';
      $this->insertRoomType = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_teacher (given_name, family_name, iniciala, ';
      $sql .= 'oddelenie, katedra, external_id) VALUES (?, ?, ?, ?, ?, ?)';
      $this->insertTeacher = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_room (name, room_type, capacity';
      $sql .= ') VALUES (?, ?, ?)';
      $this->insertRoom = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_subject (name, code, short_code, ';
      $sql .= 'credit_value, rozsah, external_id) VALUES (?, ?, ?, ?, ?, ?)';
      $this->insertSubject = $this->connection->prepare($sql);

      $sql = 'INSERT INTO tmp_insert_lesson (day, start, end, lesson_type, ';
      $sql .= 'room, subject, external_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
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
      $this->logSection('candle', 'Creating primary keys on temporary tables');

      $sql = 'ALTER TABLE tmp_insert_lesson_type';
      $sql .= ' ADD PRIMARY KEY (code)';
      $this->executeSQL($sql);

      $sql = 'ALTER TABLE tmp_insert_room_type';
      $sql .= ' ADD PRIMARY KEY (code)';
      $this->executeSQL($sql);

      $sql = 'ALTER TABLE tmp_insert_teacher';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->executeSQL($sql);

      $sql = 'ALTER TABLE tmp_insert_room';
      $sql .= ' ADD PRIMARY KEY (name)';
      $this->executeSQL($sql);

      $sql = 'ALTER TABLE tmp_insert_subject';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->executeSQL($sql);

      $sql = 'ALTER TABLE tmp_insert_lesson';
      $sql .= ' ADD PRIMARY KEY (external_id)';
      $this->executeSQL($sql);
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
      $sql .= ' t.katedra = i.katedra';
      $sql .= ' WHERE t.external_id = i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO teacher (given_name, family_name, ';
      $sql .= ' iniciala, oddelenie, katedra, external_id) ';
      $sql .= ' SELECT given_name, family_name, iniciala, oddelenie, ';
      $sql .= ' katedra, external_id';
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
      $sql .= ' l.lesson_type_id=lt.id, l.room_id=r.id, l.subject_id=s.id ';
      $sql .= ' WHERE lt.code = i.lesson_type AND r.name = i.room ';
      $sql .= ' AND s.external_id=i.subject AND l.external_id=i.external_id';
      $this->executeSQL($sql);

      $sql = 'INSERT INTO lesson (day, start, end, lesson_type_id, room_id, ';
      $sql .= ' subject_id, external_id) ';
      $sql .= 'SELECT i.day, i.start, i.end, lt.id, r.id, s.id, i.external_id ';
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

  protected function insertDataUpdate() {
      $this->logSection('candle', 'Inserting information about data update');

      $sql = 'INSERT INTO data_update';
      $sql .= ' (datetime, description)';
      $sql .= ' VALUES (NOW(), ?)';

      $prepared = $this->connection->prepare($sql);
      $this->executePreparedSQL($prepared, array($this->updateDescription));

  }
    
  protected function execute($arguments = array(), $options = array())
  {

    $databaseManager = new sfDatabaseManager($this->configuration);
    $this->connection = Doctrine_Manager::connection();
    $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';
    $this->elementData = null;
    $this->dataField = null;
    $this->state = self::STATE_ROOT;
    $this->currentElementPath = array();
    $this->ignoreErrors = $options['ignore-errors'];
    $this->printSQL = $options['print-sql'];
    $this->warningsAsErrors = $options['warnings-as-errors'];
    $this->updateDescription = $options['message'];

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

    $parser = xml_parser_create();
    xml_set_element_handler($parser, array($this, 'parser_startElement'), array($this, 'parser_endElement'));
    xml_set_character_data_handler($parser, array($this, 'parser_characterData'));
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

    // Sice som uz znizil pamatove naroky, stale treba zvysit limit
    ini_set("memory_limit","96M");

    $this->connection->beginTransaction();

    $this->connection->prepare('$statement');

    try {

        $this->createTemporaryTables();
        $this->createPrimaryKeys();
        $this->prepareInsertStatements();

        $this->logSection('candle', 'Loading xml data into database');
        $parseResult = xml_parse($parser, file_get_contents($arguments['file']));
        if ($parseResult == 0) {
            // parse failed
            $errorCode = xml_get_error_code($parser);
            $this->parseError($parser, 'Parse error '. $errorCode. ': '.xml_error_string($errorCode));
        }

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

        $this->insertDataUpdate();
        
        if ($options['dry-run']) {
            $this->logSection('Done. This is dry run, executing rollback');
            $this->connection->rollback();
        }
        else {
            $this->logSection('Done. Commiting transaction...');
            $this->connection->commit();
        }
    }
    catch (Exception $e) {
        $this->logSection('candle', 'Exception occured, executing rollback');
        $this->connection->rollback();
        $this->logBlock(array($e->getMessage(), $e->getTraceAsString()), 'ERROR');
    }

    xml_parser_free($parser);

  }
}
