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
    
    public function load($userTimetableId) {
        // TODO
    }
    
    public function save() {
        // TODO
    }
    
    public function addLesson($lesson) {
        $this->addLessonById($lesson->getId());
    }
    
    public function getLessonIds() {
        return $this->lessons;    
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
        return true;
    }

    public function unhighlightLessonById($lessonId) {
        if (!$this->hasLesson($lessonId)) return false;
        if (!isset($this->highlightedLessons[$lessonId])) return false;
        unset($this->highlightedLessons[$lessonId]);
        return true;
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

}
