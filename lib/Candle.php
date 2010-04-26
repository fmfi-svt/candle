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
    
    static public function formatClass($classes) {
        if (!$classes || count($classes) == 0) return '';
        return ' class="'.implode(' ', $classes).'" ';
    }

    static public function formatTD($classes=null, $rowspan=1) {
        return '<td'.Candle::formatClass($classes).Candle::formatRowspan($rowspan).'>';
    }
    
    static public function floorTo($number, $precision) {
        return $number - ($number % $precision);
    }
    
    static public function ceilTo($number, $precision) {
        if (($number % $precision) == 0) return $number;
        return Candle::floorTo($number, $precision) + $precision;
    }
    
    static public function dayFromCode($code) {
        $days = array('pon'=>0, 'uto'=>1, 'str'=>2, 'stv'=>3, 'pia'=>4);
        return $days[$code];
    }
    
    static public function nbsp($text) {
        return str_replace(' ', '&nbsp;', $text);
    }

    static public function formatShortName($teacher) {
        return ($teacher['given_name']?substr($teacher['given_name'], 0, 1).'. ':'').$teacher['family_name'];
    }
}
