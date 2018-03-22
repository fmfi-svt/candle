<?php

foreach ($layout->getLessons() as $lesson) {
    echo Candle::formatShortDay($lesson['day']);
    echo "\t";
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
    echo "\t";
    echo $lesson['LessonType']['name'];
    echo "\t";
    echo $lesson['Subject']['short_code'];
    echo "\t";
    echo $lesson['Subject']['name'];
    echo "\t";
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
