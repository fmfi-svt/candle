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
        $subject = Doctrine::getTable('Subject')->getSubjectById($subjectId);
        foreach ($subject->getLessons() as $lesson) {
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
        $subject = Doctrine::getTable('Subject')->getSubjectById($subjectId);
        foreach ($subject->getLessons() as $lesson) {
            $this->addLesson($lesson);
        }
    }
    
    public function addLessonById($lessonId) {
        $lesson = Doctrine::getTable('Lesson')->getLessonById($lessonId);
        $this->addLesson($lesson);
    }
    
    public function removeSubjectById($subjectId) {
        $subject = Doctrine::getTable('Subject')->getSubjectById($subjectId);
        foreach ($subject->getLessons() as $lesson) {
            $this->removeLesson($lesson);
        }
    }
    
    public function removeLessonById($lessonId) {
        unset($this->lessons[$lessonId]);
    }

}
