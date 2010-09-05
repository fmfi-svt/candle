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


/**
 * Validates day time intervals
 */
class DayTimeSpecValidator extends sfValidatorString {

    /**
     * Validate a string specifying intervals and convert
     * it into array of array(start, end) [in week times in minutes]
     *
     * Po 14:30-15:20,Ut 17:00-St 20:00, Po 14:20-15:30
     *
     * @param string $value
     * @return int
     */
    protected function doClean($value) {
        $clean = parent::doClean($value);

        $spl = explode(',', $clean);

        $items = array();

        foreach ($spl as $spec) {

            $matches = array();

            if (!preg_match('/^ *(?P<day1>[A-Za-z]{2,}) (?P<hour1>\\d{1,2}):(?P<min1>\\d{2})-(?:(?P<day2>[A-Za-z]{2,}) )?(?P<hour2>\\d{1,2}):(?P<min2>\\d{2}) *$/', $spec, $matches)) {
                throw new sfValidatorError($this, 'invalid', array('value' => $value, 'spec'=>$spec));
            }

            $day1 = $matches['day1'];
            $hour1 = intval($matches['hour1']);
            $min1 = intval($matches['min1']);
            if (!empty($matches['day2'])) {
                $day2 = $matches['day2'];
            }
            else {
                $day2 = $day1;
            }
            $hour2 = intval($matches['hour2']);
            $min2 = intval($matches['min2']);

            $dayIndex1 = Candle::parseDay($day1);
            $dayIndex2 = Candle::parseDay($day2);

            $time1 = $dayIndex1*24*60+$hour1*60+$min1;
            $time2 = $dayIndex2*24*60+$hour2*60+$min2;

            if ($time2<$time1) {
                throw new sfValidatorError($this, 'invalid', array('value' => $value, 'spec'=>$spec));
            }

            $items[] = array($time1, $time2);

        }

        return $items;
    }


}