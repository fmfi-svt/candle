<?php 
echo '"Deň","Od","Do","Predmet","Učiteľ","Miestnosť","Poznámka"';
echo "\r\n";

if ($lessonIntervals) {
    foreach ($lessonIntervals as $lesson) {
        
        echo CSV::esc(Candle::formatShortDay($lesson['day']));
        echo ',';
        echo CSV::esc(Candle::formatTime($lesson['start']));
        echo ',';
        echo CSV::esc(Candle::formatTime($lesson['end']));
        echo ',';
        echo CSV::esc($lesson['Subject']['name']);
        echo ',';
        $first = true;
        $teachers = '';
        foreach ($lesson['Teacher'] as $teacher) {
            if (!$first) $teachers .= ', ';
            $first = false;
            $teachers .= Candle::formatLongName($teacher);
        }
        echo CSV::esc($teachers);
        echo ',';
        echo CSV::esc($lesson['Room']['name']);
        echo ',';
        echo CSV::esc($lesson['note']);
        echo "\r\n";
        
    }
}
