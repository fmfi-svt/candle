"Deň","Začiatok","Koniec","Trvanie","Miestnosť","Forma","Názov predmetu","Vyučujúci"
<?php

foreach ($layout->getLessons() as $lesson) {
    echo CSV::esc(Candle::formatShortDay($lesson['day']));
    echo ',';
    echo CSV::esc(Candle::formatTime($lesson['start']));
    echo ',';
    echo CSV::esc(Candle::formatTime($lesson['end']));
    echo ',';
    if ($layout->isFMPHLike()) {
        $trvanie = (($lesson['end']-$lesson['start'])/45).' v.hod.';
    }
    else {
        $trvanie = ($lesson['end']-$lesson['start']).' min.';
    }
    echo CSV::esc($trvanie);
    echo ',';
    echo CSV::esc($lesson['Room']['name']);
    echo ',';
    echo CSV::esc($lesson['LessonType']['name']);
    echo ',';
    echo CSV::esc($lesson['Subject']['name']);
    echo ',';
    $teachers = '';
    $first = true;
    foreach ($lesson['Teacher'] as $teacher) {
        if (!$first) {
            $teachers .= ', ';
        }
        $first = false;
        $teachers .= Candle::formatShortName($teacher);
    }
    echo CSV::esc($teachers);
    echo "\r\n"; // vid rfc uvedene vyssie
}
