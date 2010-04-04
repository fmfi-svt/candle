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
    
    public function addLessons($list) {
        // TODO
    }
    
    public function getLessons() {
        return $this->lessons;    
    }
    
    public function removeLessons($list) {
        // TODO
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
    
    public function isSubjectSelected($subjectId) {
        // TODO
    }
    
    public function __toString() {
        return $this->getName();
    }
    
    public function isModified() {
        return $this->isModified;
    }

}
