<?php

/**

    Copyright 2010 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */


class Candle {
    static public function formatTime($timeVal) {
        $h = intval($timeVal/60);
        $m = ''.$timeVal%60;
        if (strlen($m)<2) {
            $m = '0'.$m;
        }
        return $h.':'.$m;
    }

    static public function formatTimeAmount($timeVal) {
        $ret = '';

        if ($timeVal == 0) return '-';

        $h = intval($timeVal/60);
        $m = $timeVal % 60;

        if ($h > 0) {
            $ret .= $h.'h';
        }

        if ($h > 0 && $m > 0) {
            $ret .= ' ';
        }

        if ($m > 0) {
            $ret .= $m.'m';
        }
        
        return $ret;
    }
    
    static public function formatShortDay($dayNum) {
        $days = array('Po', 'Ut', 'St', 'Št', 'Pi');
        return $days[$dayNum];
    }
    
    static public function formatLongDay($dayNum) {
        $days = array('Pondelok', 'Utorok', 'Streda', 'Štvrtok', 'Piatok');
        return $days[$dayNum];
    }

    /**
     * Parse day from day name. If ambiguous, return day that is earlier.
     * If bad day, return false.
     * @param string $name
     */
    static public function parseDay($name) {
        $days = array(array('pondelok'),
                    array('utorok'),
                    array('streda'),
                    array('štvrtok', 'stvrtok'),
                    array('piatok'));

        $name = self::lower($name);

        foreach ($days as $key=>$day) {
            foreach ($day as $variant) {
                if (self::startsWith($variant, $name)) {
                    return $key;
                }
            }
        }

        return false;
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
        $old_encoding = mb_internal_encoding();

        if (isset($teacher['given_name'])) {
            $given_name = $teacher['given_name'];
        } else {
            $given_name = null;
        }
        
        mb_internal_encoding(mb_detect_encoding($given_name));
        $shortName = ($given_name?mb_substr($given_name, 0, 1).'. ':'').$teacher['family_name'];
        mb_internal_encoding($old_encoding);
        return $shortName;
    }

    static public function formatLongName($teacher) {
        return ($teacher['given_name']?$teacher['given_name'].' ':'').$teacher['family_name'];
    }

    static public function setTimetableExportResponse(sfWebRequest $request, sfActions $actions) {
        self::setResponseFormat($request->getRequestFormat(), $actions);
    }

    static public function setResponseFormat($format, sfActions $actions) {
        switch ($format)
        {
            case 'csv':
                $actions->setLayout(false);
                $actions->getResponse()->setContentType('text/csv;header=present'); // vid RFC 4180
                break;
            case 'ics':
                $actions->setLayout(false);
                $actions->getResponse()->setContentType('text/calendar'); // vid RFC 2445
                break;
            case 'list':
                $actions->setLayout(false);
                $actions->getResponse()->setContentType('text/plain');
                break;
        }
    }

    static public function getLessonTypeHTMLClass($lessonType) {
        return 'lesson-type-'.strtoupper($lessonType['code']);
    }

    static public function addFormat(array $url, $format) {
        return array_merge($url, array('sf_format'=>$format));
    }

    /**
     * PriF/1-UBI-004-1/7064/00 -> 1-UBI-004-1
     * @param string $longCode
     * @return string short code
     */
    static public function subjectShortCodeFromLongCode($longCode) {
        $firstSlash = strpos($longCode, '/');
        if ($firstSlash === false) return false;
        $secondSlash = strpos($longCode, '/', $firstSlash+1);
        if ($secondSlash === false) return false;
        return substr($longCode, $firstSlash+1, $secondSlash-$firstSlash-1);
    }

    /**
     * 1-UBI-004-1 -> 1-UBI-004
     * @param string $shortCode
     * @return string shorter code
     */
    static public function subjectShorterCode($shortCode) {
        $matches = array();
        if (!preg_match('@^(\d-[A-Z]{3}-\d{3})(?:-\d)?$@', $shortCode, $matches)) {
            return false;
        }
        return $matches[1];
    }

    static public function upper($string) {
        $old_encoding = mb_internal_encoding();

        mb_internal_encoding(mb_detect_encoding($string));
        $result = mb_strtoupper($string);
        mb_internal_encoding($old_encoding);
        return $result;
    }

    static public function lower($string) {
        $old_encoding = mb_internal_encoding();

        mb_internal_encoding(mb_detect_encoding($string));
        $result = mb_strtolower($string);
        mb_internal_encoding($old_encoding);
        return $result;
    }

    /**
     * Decide if string $a starts with string $b
     * @param string $a
     * @param string $b
     */
    static public function startsWith($a, $b, $caseInsensitive=false) {
        if (strlen($b) == 0) return true;
        if (strlen($b)>strlen($a)) return false;

        return substr_compare($a, $b, 0, strlen($b), $caseInsensitive) === 0;
    }

    /**
     * Parse a date in YYYY-MM-DD format
     * and return it in a unix timestamp
     *
     * @param string $date
     * @return int the timestamp or false on error
     */
    static public function parseDate($date) {
        $matches = array();

        if (!preg_match('/^(\\d{4})-(\\d{2})-(\\d{2})$/', $date, $matches)) {
            return false;
        }

        return mktime(0,0,0,intval($matches[2]), intval($matches[3]), intval($matches[1]));
    }

    static public function makeSubjectInfoLink($shortCode) {
        $matches = array();
        if (preg_match('/^(\d-INF-\d{3})(?:-\d+){0,2}$/',$shortCode, $matches)) {
            return 'http://new.dcs.fmph.uniba.sk/index.php/'.urlencode($matches[1]);
        }
        return null;
    }
}
