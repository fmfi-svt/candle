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
    
    static public function formatRowspan($rowspan) {
        if ($rowspan <= 1) {
            return '';
        }
        else {
            return ' rowspan="'.$rowspan.'" ';
        }
    }
    
    static public function floorTo($number, $precision) {
        return $number - ($number % $precision);
    }
    
    static public function ceilTo($number, $precision) {
        if (($number % $precision) == 0) return $number;
        return Candle::floorTo($number, $precision) + $precision;
    }
}
