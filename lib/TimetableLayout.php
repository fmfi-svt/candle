<?php

class TimetableLayout {

    private $timetable = null;
    private $lessonMinTime = 0;
    private $lessonMaxTime = 1440;
    private $isFMPHlike = false;

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
        $this->lessonMinTime = 24*60;
        $this->lessonMaxTime = 0;
        $this->isFMPHLike = true; // kazda hodina nespravnej dlzky a casu to moze pokazit
        foreach($this->timetable->getLessons() as $lesson) {
            $byDays[$lesson->getDay()][] = $lesson;
            $this->lessonMinTime = min($this->lessonMinTime, $lesson->getStart());
            $this->lessonMaxTime = max($this->lessonMaxTime, $lesson->getEnd());
            if (($lesson->getStart() % 50) != 40 || ($lesson->getLength() % 45 != 0)) {
                // toto nie je FMFI hodina
                $this->isFMPHLike = false;
            }
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
        
        // Dokoncenie layoutu
        $this->days = $layout;
    }   

    public function getDays() {
        return $this->days;
    }
    
    public function isFMPHLike() {
        return $this->isFMPHLike;
    }
    
    public function getLessonMinTime() {
        return $this->lessonMinTime;
    }
    
    public function getLessonMaxTime() {
        return $this->lessonMaxTime;
    }
    
    private function isFree($column, $lesson) {
        // iba posledna vec, musi byt utriedene podla casu!
        if (count($column) == 0) return True;
        $last = $column[count($column)-1];
        return ($last->getEnd() <= $lesson->getStart());
    }

}
