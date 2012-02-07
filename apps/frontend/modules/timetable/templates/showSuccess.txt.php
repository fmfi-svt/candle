<?php

foreach ($layout->getLessons() as $lesson) {
    echo Candle::formatShortDay($lesson['day']);
    echo ' ';
    echo Candle::formatTime($lesson['start']);
    echo ' - ';
    echo Candle::formatTime($lesson['end']);
    echo ' (';
    if ($layout->isFMPHLike()) {
        echo ($lesson['end']-$lesson['start'])/45;
        echo ' v.hod.) ';
    }
    else {
        echo $lesson['end']-$lesson['start'];
        echo ' min.) ';
    }
    echo $lesson['Room']['name'];
    echo ' ';
    echo $lesson['LessonType']['name'];
    echo ' ';
    echo $lesson['Subject']['name'];
    echo ' ';
    if ($lesson['note'] !== null) {
        echo '(';
        echo $lesson['note'];
        echo ') ';
    }
    $first = true;
    foreach ($lesson['Teacher'] as $teacher) {
        if (!$first) {
            echo ', ';
        }
        $first = false;
        echo Candle::formatShortName($teacher);
    }
    echo "\n";
}
