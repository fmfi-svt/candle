<?php 
echo '"Deň","Od","Do","Dĺžka","Dĺžka (min.)","Miestnosť","Kapacita","Typ"';
echo "\r\n";

if ($roomIntervals) {
    foreach ($roomIntervals as $interval) {
        foreach ($interval['printableIntervals'] as $timeInterval) {
            echo CSV::esc(Candle::formatShortDay($timeInterval->getStartDay()));
            echo ',';
            echo CSV::esc(Candle::formatTime($timeInterval->getStartTime()));
            echo ',';
            if (!$timeInterval->isEmpty() && $timeInterval->getEndTime() == 0) {
                echo CSV::esc('24:00');
            }
            else {
                echo CSV::esc(Candle::formatTime($timeInterval->getEndTime()));
            }
            echo ',';
            echo CSV::esc(Candle::formatTimeAmount($timeInterval->getLength()));
            echo ',';
            echo CSV::esc($timeInterval->getLength());
            echo ',';
            echo CSV::esc($interval['Room']['name']);
            echo ',';
            echo CSV::esc($interval['Room']['capacity']);
            echo ',';
            echo CSV::esc($interval['Room']['RoomType']['name']);
            echo "\r\n";
        }
    }
}
