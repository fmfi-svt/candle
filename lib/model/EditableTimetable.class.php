<?php

class EditableTimetable {
    
    private $name = '';
    private $lessons = array();
    private $highlightedLessons = array();
    private $userTimetableId = null;
    private $modified = false;
    private $readOnly = false;
    
    public function __construct() {
    }
    
    public function load($userTimetable) {
        $this->name = $userTimetable['name'];
        $this->lessons = array();
        $this->highlightedLessons = array();
        $this->userTimetableId = $userTimetable['id'];

        foreach ($userTimetable['UserTimetableLessons'] as $utl) {
            $this->addLessonById($utl['lesson_id']);
            if ($utl['highlighted']) {
                $this->highlightLessonById($utl['lesson_id']);
            }
        }

        $this->modified = false;
    }
    
    public function save($userId) {
        $connection = Doctrine_Manager::connection();

        $connection->beginTransaction();

        try {

            if (!$this->isPersisted()) {
                $userTimetable = new UserTimetable();
            }
            else {
                $userTimetable = Doctrine::getTable('UserTimetable')->find($this->userTimetableId);
            }
            $userTimetable['name'] = $this->getName();
            $userTimetable['user_id'] = $userId;
            
            $userTimetable->save();
            
            Doctrine::getTable('UserTimetableLessons')->deleteForUserTimetableId($userTimetable['id']);

            foreach ($this->lessons as $lesson) {
                $utl = new UserTimetableLessons();
                $utl['user_timetable_id'] = $userTimetable['id'];
                $utl['lesson_id'] = $lesson;
                $utl['highlighted'] = $this->isLessonHighlighted($lesson);
                $utl->save();
            }
            
            $connection->commit();

            $this->userTimetableId = $userTimetable['id'];
            $this->modified = false;
        }
        catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
    }

    public function delete() {
        Doctrine::getTable('UserTimetable')->deleteById($this->userTimetableId);
    }
    
    public function addLesson($lesson) {
        $this->addLessonById($lesson->getId());
    }
    
    public function getLessonIds() {
        return array_keys($this->lessons);
    }
    
    public function getLessons() {
        //return array();
        return Doctrine::getTable('Lesson')->fetchFullLessons(array_keys($this->lessons));
    }
    
    public function removeLesson($lesson) {
        $this->removeLessonById($lesson->getId());
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
    
    public function hasLesson($lessonId) {
        $lessonId = intval($lessonId);
        return isset($this->lessons[$lessonId]);
    }

    public function isLessonHighlighted($lessonId) {
        $lessonId = intval($lessonId);
        return isset($this->highlightedLessons[$lessonId]);
    }

    public function highlightLessonById($lessonId) {
        $lessonId = intval($lessonId);
        if (!$this->hasLesson($lessonId)) return false;
        if (isset($this->highlightedLessons[$lessonId])) return false;
        $this->highlightedLessons[$lessonId] = $lessonId;
        $this->modified = true;
        return true;
    }

    public function unhighlightLessonById($lessonId) {
        if (!$this->hasLesson($lessonId)) return false;
        if (!isset($this->highlightedLessons[$lessonId])) return false;
        unset($this->highlightedLessons[$lessonId]);
        $this->modified = true;
        return true;
    }

    public function getHighlightedLessonIds() {
        return array_keys($this->highlightedLessons);
    }
    
    public function isSubjectSelected($subjectId) {
        $subjectId = intval($subjectId);
        $lessons = Doctrine::getTable('Lesson')->listBySubjectId($subjectId);
        foreach ($lessons as $lesson) {
            if (!$this->hasLesson($lesson->getId())) {
                return false;
            }
        }
        return true;
    }
    
    public function __toString() {
        return $this->getName();
    }
    
    public function isModified() {
        return $this->modified;
    }

    public function isReadOnly() {
        return $this->readOnly;
    }

    public function isPersisted() {
        return $this->userTimetableId !== null;
    }

    public function getUserTimetableId() {
        return $this->userTimetableId;
    }
    
    public function addSubjectById($subjectId) {
        $subjectId = intval($subjectId);
        $lessons = Doctrine::getTable('Lesson')->listBySubjectId($subjectId);
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }
    }
    
    public function addLessonById($lessonId) {
        $lessonId = intval($lessonId);
        if (!isset($this->lessons[$lessonId])) {
            $this->lessons[$lessonId] = $lessonId;
            $this->modified = true;
            return true;
        }
        return false;
    }
    
    public function removeSubjectById($subjectId) {
        $subjectId = intval($subjectId);
        $lessons = Doctrine::getTable('Lesson')->listBySubjectId($subjectId);
        foreach ($lessons as $lesson) {
            $this->removeLesson($lesson);
        }
    }
    
    public function removeLessonById($lessonId) {
        $lessonId = intval($lessonId);
        if (isset($this->lessons[$lessonId])) {
            unset($this->lessons[$lessonId]);
            unset($this->highlightedLessons[$lessonId]);
            $this->modified = true;
            return true;
        }
        return false;
    }

    public function __clone() {
        $this->userTimetableId = null;
        $this->readOnly = false;
        $this->modified = true;
    }

}
