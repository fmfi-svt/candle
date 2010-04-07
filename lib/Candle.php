<?php

class Candle {
    static public function formatTime($timeVal) {
        $h = intval($timeVal/60);
        $m = ''.$timeVal%60;
        if (strlen($m)<2) {
            $m = '0'.$m;
        }
        return $h.':'.$m;
    }
    
    static public function formatShortDay($dayNum) {
        $days = array('Po', 'Ut', 'St', 'Št', 'Pi');
        return $days[$dayNum];
    }
    
    static public function formatLongDay($dayNum) {
        $days = array('Pondelok', 'Utorok', 'Streda', 'Štvrtok', 'Piatok');
        return $days[$dayNum];
    }
}
