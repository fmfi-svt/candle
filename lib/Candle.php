<?php

class Candle {
    static public function formatTime($timeVal) {
        $h = intval($timeVal/60);
        $m = $timeVal%60;
        return $h.':'.$m;
    }
    
    static public function formatShortDay($dayNum) {
        $days = array('Po', 'Ut', 'St', 'Št', 'Pi');
        return $days[$dayNum];
    }
}
