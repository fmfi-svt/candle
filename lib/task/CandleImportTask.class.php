<?php

class CandleImportTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
        new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'The file to load'),
    ));    
    
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application', 'frontend'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environement', 'prod'),
    ));
 
    $this->namespace = 'candle';
    $this->name = 'import';
    $this->briefDescription = 'Load rozvrh.xml with lesson definitions';
 
    $this->detailedDescription = <<<EOF
The [candle:import|INFO] task loads the rozvrh.xml into database,
replacing any definitions currently there:
 
  [./symfony candle:import --env=prod ~/rozvrh.xml|INFO]
EOF;
  }
 
  protected function execute($arguments = array(), $options = array())
  {
    
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = Doctrine_Manager::connection();
    $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';
    

    if (!$this->askConfirmation(array_merge(
        array(sprintf('This command will remove source timetable data in the "%s" connection.', $environment), ''),
        array('', 'Are you sure you want to proceed? (y/N)')
      ), 'QUESTION_LARGE', false)
    )
    {
      $this->logSection('candle', 'task aborted');

      return 1;
    }
    
    // Nacitat cele to xml-ko do pamate zere celkom vela...
    // FIXME prerobit skript tak, aby pouzival menej pamate
    ini_set("memory_limit","160M");    

    $lessonTypes = array();
    $roomTypes = array();
    $teachers = array();
    $rooms = array();
    $subjects = array();
    $lessons = array();
    $studentGroups = array();
    $lessonLinks = array();
    
    libxml_use_internal_errors(true);
    $this->logSection('candle', 'Starting to load source file: '.$arguments['file']);
    $xmlRoot = simplexml_load_file($arguments['file']);
    $errors = libxml_get_errors();
    if ($xmlRoot === false || $errors) {
        $errorMessages = 'Error reading xml file';
        foreach($errors as $error) {
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $errorMessages .= "\n\n  WARNING $error->code: ";
                    break;
                 case LIBXML_ERR_ERROR:
                    $errorMessages .= "\n\n  ERROR $error->code: ";
                    break;
                case LIBXML_ERR_FATAL:
                    $errorMessages .= "\n\n  FATAL ERROR $error->code: ";
                    break;
            }
            $errorMessages .= trim($error->message)."\n";
            $errorMessages .= '    (Line: '.$error->line.' Column: '.$error->column.")\n";
        }
        $this->logBlock($errorMessages, 'ERROR');
        return 1;
    }
    
    $errors = 0;
    
    $this->logSection('candle', 'Extracting data from xml');

    foreach($xmlRoot->typy->typ as $xmlTyp) {
        $type = new LessonType();
        $type->setCode((string) $xmlTyp['id']);
        $type->setName((string) $xmlTyp['popis']);

        if (strlen($type->getCode()) != 1) {
            $this->logBlock('Incorrect typ.id length (should be 1): '.$type->getCode(), 'ERROR');
            $errors++;
            continue;
        }
        
        if (isset($lessonTypes[$type->getCode()])) {
            $this->logBlock('Duplicate typ.id: '. $type->getCode(), 'ERROR');
            $errors++;
            continue;
        }

        $lessonTypes[$type->getCode()] = $type;
    }
    
    foreach($xmlRoot->typymiestnosti->typmiestnosti as $xmlTypMiestnosti) {
        $roomType = new RoomType();
        $roomType->setCode((string) $xmlTypMiestnosti['id']);
        $roomType->setName((string) $xmlTypMiestnosti['popis']);
 
        if (strlen($roomType->getCode()) != 1) {
            $this->logBlock('Incorrect typmiestnosti.id length (should be 1): '.$roomType->getCode(), 'ERROR');
            $errors++;
            continue;
        }
        
        if (isset($roomTypes[$roomType->getCode()])) {
            $this->logBlock('Duplicate typmiestnosti.id: '. $roomType->getCode(), 'ERROR');
            $errors++;
            continue;
        }

       $roomTypes[$roomType->getCode()] = $roomType;
    }
    
    foreach($xmlRoot->ucitelia->ucitel as $xmlUcitel) {
        $teacher = new Teacher();
        $teacher->setExternalId((string) $xmlUcitel['id']);
        $teacher->setFamilyName((string) $xmlUcitel->priezvisko);
        $teacher->setGivenName((string) $xmlUcitel->meno);
        $teacher->setIniciala((string) $xmlUcitel->iniciala);
        $teacher->setKatedra((string) $xmlUcitel->katedra);
        $teacher->setOddelenie((string) $xmlUcitel->oddelenie);
        
        if (isset($teachers[$teacher->getExternalId()])) {
            $this->logBlock('Duplicate ucitel.id: '. $teacher->getExternalId(), 'ERROR');
            $errors++;
            continue;
        }

        $teachers[$teacher->getExternalId()] = $teacher;
    }
    
    foreach($xmlRoot->miestnosti->miestnost as $xmlMiestnost) {
        $room = new Room();
        $room->setName((string) $xmlMiestnost->nazov);
        $room->setCapacity((int) $xmlMiestnost->kapacita);
        $room->setRoomType($roomTypes[(string) $xmlMiestnost->typ]);
        
        if (isset($rooms[$room->getName()])) {
            $this->logBlock('Duplicate miestnost.nazov: '. $room->getName(), 'ERROR');
            $errors++;
            continue;
        }
        
        $rooms[$room->getName()] = $room;
    }
    
    foreach($xmlRoot->predmety->predmet as $xmlPredmet) {
        $subject = new Subject();
        $subject->setExternalId((string) $xmlPredmet['id']);
        $subject->setName((string) $xmlPredmet->nazov);
        $subject->setCode((string) $xmlPredmet->kod);
        $subject->setShortCode((string) $xmlPredmet->kratkykod);
        $subject->setCreditValue((int) $xmlPredmet->kredity);
        $subject->setRozsah((string) $xmlPredmet->rozsah);
        $subjects[$subject->getExternalId()] = $subject;
    }
    
    foreach($xmlRoot->hodiny->hodina as $xmlHodina) {
        $lesson = new Lesson();
        $lesson->setExternalId((int) $xmlHodina['id']);
        $lesson->setDay(Candle::dayFromCode((string) $xmlHodina->den));
        $lesson->setStart((int) $xmlHodina->zaciatok);
        $lesson->setEnd((int) $xmlHodina->koniec);
        $lesson->setRoom($rooms[(string) $xmlHodina->miestnost]);
        if (!$subjects[(string) $xmlHodina->predmet]) {
            $this->logBlock('Undefined hodina.predmet: '. (string) $xmlHodina->predmet, 'ERROR');
            $errors++;
            continue;
        }
        $lesson->setSubject($subjects[(string) $xmlHodina->predmet]);

        $ucitelia = explode(',', (string) $xmlHodina->ucitelia);
        foreach ($ucitelia as $ucitel_id) {
            $lesson->Teacher[] = $teachers[ucitel_id];
        }

        $kruzky = explode(',', (string) $xmlHodina->kruzky);
        foreach ($kruzky as $kruzok) {
            if (!isset($studentGroups[$kruzok])) {
                $studentGroups[$kruzok] = new StudentGroup();
                $studentGroups[$kruzok]->setName($kruzok);
            }
            $lesson->StudentGroup[] = $studentGroups[$kruzok];
        }
        $lesson->setLessonType($lessonTypes[(string) $xmlHodina->typ]);

        if (isset($lessons[$lesson->getExternalId()])) {
            $this->logBlock('Duplicate hodina.id: '. $lesson->getExternalId(), 'ERROR');
            $errors++;
            continue;
        }

        $zviazanehodiny = explode(',', (string) $xmlHodina->zviazanehodiny);
        $lessonLinks[$lesson->getExternalId()] = array();
        foreach ($zviazanehodiny as $zviazanahodina) {
            $lessonLinks[$lesson->getExternalId()][] = (int) $zviazanahodina;
        }
    
        $lessons[$lesson->getExternalId()] = $lesson;
    }
    
    // Nastav zviazane hodiny (toto treba robit az ked mam vsetky hodiny)
    foreach ($lessons as $lesson) {
        foreach ($lessonsLinks[$lesson->getExternalId()] as $link) {
            $lesson->Linked[] = $lessons[$link];
        }
    }
    
    if ($errors) {
        $this->logBlock(sprintf('Encountered %d errors, so not going forward', $errors), 'ERROR_LARGE');
        return 1;
    }
    
    $this->logSection('candle', 'Data extracted successfully');
 
    $connection->beginTransaction();

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
    
    $this->logSection('candle', 'Saving lesson types');        
    foreach ($lessonTypes as $lessonType) {
        $lessonType->save();
    }

    $this->logSection('candle', 'Saving room types');        
    foreach ($roomTypes as $roomType) {
        $roomType->save();
    }
    
    $this->logSection('candle', 'Saving teachers');
    foreach ($teachers as $teacher) {
        $teacher->save();
    }
    
    $this->logSection('candle', 'Saving rooms');
    foreach ($rooms as $room) {
        $room->save();
    }
    
    $this->logSection('candle', 'Saving subjects');
    foreach ($subjects as $subject) {
        $subject->save();
    }
    
    $this->logSection('candle', 'Saving student groups');
    foreach ($studentGroups as $studentGroup) {
        $studentGroup->save();
    }
    
    $this->logSection('candle', 'Saving lessons');
    foreach ($lessons as $lesson) {
        $lesson->save();
    }
    
    $connection->commit();

    $this->logSection('candle', sprintf('Replaced %d lesson types with %d new (%+d)', $deletedLessonTypes, count($lessonTypes), count($lessonTypes)-$deletedLessonTypes));
    $this->logSection('candle', sprintf('Replaced %d room types with %d new (%+d)', $deletedRoomTypes, count($roomTypes), count($roomTypes)-$deletedRoomTypes));
    $this->logSection('candle', sprintf('Replaced %d teachers with %d new (%+d)', $deletedTeachers, count($teachers), count($teachers)-$deletedTeachers));
    $this->logSection('candle', sprintf('Replaced %d rooms with %d new (%+d)', $deletedRooms, count($rooms), count($rooms)-$deletedRooms));
    $this->logSection('candle', sprintf('Replaced %d subjects with %d new (%+d)', $deletedSubjects, count($subjects), count($subjects)-$deletedSubjects));
    $this->logSection('candle', sprintf('Replaced %d student groups with %d new (%+d)', $deletedStudentGroups, count($studentGroups), count($studentGroups)-$deletedStudentGroups));
    $this->logSection('candle', sprintf('Replaced %d lessons with %d new (%+d)', $deletedLessons, count($lessons), count($lessons)-$deletedLessons));
  }
}
