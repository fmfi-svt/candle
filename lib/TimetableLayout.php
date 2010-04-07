<?php

class TimetableLayout {

    private $timetable = null;
    private $lessonMinTime = 0;
    private $lessonMaxTime = 1440;
    private $layoutStartTime = 0;
    private $layoutEndTime = 1440;
    

    /** Pole dni v rozvrhu
    *
    * Presnejsie pole poli poli hodin
    * 
    * Jednotlive urovne pola znamenaju:
    *   * Den
    *   * Stlpec v dni
    *   * Zoznam hodin v stlpci
    */
    private $days = array();
    
    public function __construct(EditableTimetable $timetable) {
        $this->timetable = $timetable;
        $this->doLayout();
    }

    private static function compareLessonsByStartTime($a, $b) {
        if ($a->getStart() == $b->getStart()) return 0;
        return ($a->getStart() < $b->getStart()) ? -1 : 1 ;
    }
    
    public function doLayout() {
        $byDays = array();
        $layout = array();
        for ($day = 0; $day < 5; $day++) {
            $byDays[] = array();
            $layout[] = array(array());
        }
        // Najprv rozdelim hodiny podla dni a spocitam niektore statistiky
        $this->minTime = 24*60;
        $this->maxTime = 0;
        foreach($this->timetable->getLessons() as $lesson) {
            $byDays[$lesson->getDay()][] = $lesson;
            $this->minTime = min($this->minTime, $lesson->getStart());
            $this->maxTime = max($this->maxTime, $lesson->getEnd());
        }
        // Potom utriedim hodiny v dnoch podla casu
        for ($day = 0; $day < 5; $day++) {
            usort($byDays[$day], array('TimetableLayout', 'compareLessonsByStartTime')); // TODO mozno presunut porovnavaciu funkciu do lesson?
        }
        // Dalej rozhodim hodiny do stlpcov v dnoch
        for ($day = 0; $day < 5; $day++) {
            foreach($byDays[$day] as $lesson) {
                $added = false;
                foreach($layout[$day] as &$column) {
                    if ($this->isFree($column, $lesson)) {
                        $column[] = $lesson;
                        $added = true;
                        break;
                    }
                }
                if (!$added) {
                    $layout[$day][] = array($lesson);
                }
            }
        }
        $this->days = $layout;
    }   

    public function getDays() {
        return $this->days;
    }
    
    public function isFree($column, $lesson) {
        // iba posledna vec, musi byt utriedene podla casu!
        if (count($column) == 0) return True;
        $last = $column[count($column)-1];
        return ($last->getEnd() <= $lesson->getStart());
    }

}
