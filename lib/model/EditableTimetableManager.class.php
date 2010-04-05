<?php

class EditableTimetableManager {
    
    private $timetables = array();
    
    public function __construct() {
        $defaultTimetable = new EditableTimetable();
        $defaultTimetable->setName('Vlastný rozvrh');
        $this->timetables[] = $defaultTimetable;
    }
    
    public function addTimetable(EditableTimetable $timetable) {
        $this->timetables[] = $timetable;
    }
    
    public function removeTimetable($index) {
        // TODO
    }
    
    public function getTimetable($index) {
        if (!isset($this->timetables[$index])) return null;
        return $this->timetables[$index];
    }
    
    public function getTimetables() {
        return $this->timetables;
    }

}
