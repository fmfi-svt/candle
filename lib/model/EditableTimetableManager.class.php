<?php

class EditableTimetableManager {
    
    private $timetables = array();
    
    public function __construct() {
        $defaultTimetable = new EditableTimetable();
        $defaultTimetable->setName('Vlastný rozvrh');
        $this->timetables[] = $defaultTimetable;
    }
    
    public function addTimetable(EditableTimetable $timetable) {
        $this->ensureUniqueName($timetable);
        $this->timetables[] = $timetable;
        return key($this->timetables);
    }
    

    /**
     * Zaisti, aby tento rozvrh nemal rovnaky nazov ako nejaky iny
     * @param timetable rozvrh, ktory sa nenachadza v tomto manageri
    */
    public function ensureUniqueName($timetable) {
        $names = array();
        foreach($this->timetables as $existingTimetable) {
            $names[$existingTimetable->getName()] = $timetable;
        }
        if (!isset($names[$timetable->getName()])) return false;
        $index = 2;
        while (true) {
            $newName = $timetable->getName().' ('.$index.')';
            if (!isset($names[$newName])) {
                $timetable->setName($newName);
                return true;
            }
            $index++;
        }
        throw new Exception("Shouldn't be here ;)");
    }
    
    public function removeTimetable($index) {
        $timetable = false;
        if (isset($this->timetables[$index])) {
            $timetable = $this->timetables[$index];
            unset($this->timetables[$index];
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
