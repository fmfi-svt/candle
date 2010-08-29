<?php

/**

    Copyright 2010 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */

class EditableTimetableManager {
    
    private $timetables = array();
    
    public function __construct() {
    }
    
    public function addTimetable(EditableTimetable $timetable, $id=null) {
        $timetable->setName($this->getUniqueName($timetable->getName()));
        if ($id == null || isset($this->timetables[$id])) {
            $this->timetables[] = $timetable;
        }
        else {
            $this->timetables[$id] = $timetable;
        }
        return key($this->timetables);
    }

    public function getMaxId() {
        return max(array_keys($this->timetables));
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

    /**
     * Remove timetable and return the removed timetable
     */
    public function removeTimetable($index) {
        $timetable = false;
        if (isset($this->timetables[$index])) {
            $timetable = $this->timetables[$index];
            if ($timetable->isPersisted()) {
                $timetable->delete();
            }
            unset($this->timetables[$index]);
        }
        if ($this->isEmpty()) {
            $this->addDefaultTimetable();
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

    public function isEmpty() {
        return count($this->timetables) == 0;
    }

    public function getFirstTimetableId() {
        reset($this->timetables);
        return key($this->timetables);
    }

    public function addDefaultTimetable() {
        $defaultTimetable = new EditableTimetable();
        $defaultTimetable->setName('Rozvrh');
        return $this->addTimetable($defaultTimetable);
    }
    
}
