<?php

class EditableTimetable {
    
    private $name = '';
    private $lessons = array();
    private $selectedLessons = array();
    private $userTimetableId = null;
    private $isModified = false;
    
    public function __construct() {
    }
    
    public function load($userTimetableId) {
        // TODO
    }
    
    public function save() {
        // TODO
    }
    
    public function duplicate() {
        // TODO
    }
    
    public function addLesson($lesson) {
        $this->lessons[$lesson->getId()] = $lesson;
    }
    
    public function getLessons() {
        return $this->lessons;    
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
        return isset($this->lessons[$lessonId]);
    }
    
    public function isSubjectSelected($subjectId) {
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
        return $this->isModified;
    }
    
    public function addSubjectById($subjectId) {
        $lessons = Doctrine::getTable('Lesson')->listBySubjectId($subjectId);
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }
    }
    
    public function addLessonById($lessonId) {
        $lesson = Doctrine::getTable('Lesson')->find($lessonId);
        $this->addLesson($lesson);
    }
    
    public function removeSubjectById($subjectId) {
        $lessons = Doctrine::getTable('Lesson')->listBySubjectId($subjectId);
        foreach ($lessons as $lesson) {
            $this->removeLesson($lesson);
        }
    }
    
    public function removeLessonById($lessonId) {
        unset($this->lessons[$lessonId]);
    }

}
