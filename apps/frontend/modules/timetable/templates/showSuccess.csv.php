"Deň","Začiatok","Koniec","Trvanie","Miestnosť","Forma","Názov predmetu","Vyučujúci","Poznámka"
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
    $teachers = Candle::formatShortNameList($lesson['Teacher']);
    echo CSV::esc($teachers);
    echo ',';
    echo CSV::esc(($lesson['note'] === null)?'':$lesson['note']);
    echo "\r\n"; // vid rfc uvedene vyssie
}
