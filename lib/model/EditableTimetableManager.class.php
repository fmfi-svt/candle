<?php

class EditableTimetableManager {
    
    private $timetables = array();
    
    public function __construct() {
    }
    
    public function addTimetable(EditableTimetable $timetable) {
        $timetable->setName($this->getUniqueName($timetable->getName()));
        $this->timetables[] = $timetable;
        return key($this->timetables);
    }
    
    public function duplicateTimetable(EditableTimetable $timetable) {
        $duplicate = clone $timetable;
        return $this->addTimetable($duplicate);
    }
    

    /**
     * Zaisti, aby tento rozvrh nemal rovnaky nazov ako nejaky iny
     * @param string nazov rozvrhu, ktory ma byt upraveny, aby bol unikatny
    */
    public function getUniqueName($name) {
        $matches = array();
        if (preg_match('/^(.*) \\(\\d+\\)$/', $name, $matches)) {
            $name = $matches[1];
        }
        $names = array();
        foreach($this->timetables as $existingTimetable) {
            $names[$existingTimetable->getName()] = true;
        }
        if (!isset($names[$name])) return $name;
        $index = 2;
        while (true) {
            $newName = $name.' ('.$index.')';
            if (!isset($names[$newName])) {
                return $newName;
            }
            $index++;
        }
        throw new Exception("Shouldn't be here ;)");
    }
    
    public function removeTimetable($index) {
        $timetable = false;
        if (isset($this->timetables[$index])) {
            $timetable = $this->timetables[$index];
            unset($this->timetables[$index]);
        }
        return $timetable;
    }
    
    public function getTimetable($index) {
        if (!isset($this->timetables[$index])) return null;
        return $this->timetables[$index];
    }
    
    public function getTimetables() {
        return $this->timetables;
    }

}
