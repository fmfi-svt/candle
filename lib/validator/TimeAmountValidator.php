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
 * Validates amount of time and converts it to a number of minutes
 */
class TimeAmountValidator extends sfValidatorString {

    /**
     * Validate a string specifying amount of time and convert it
     * to a number of minutes
     *
     * It recognizes several formats:
     * ## h,? ## m
     * ## h
     * ##[.,]## h
     * ## m
     * ##:##
     *
     * @param string $value
     * @return int
     */
    protected function doClean($value) {
        $clean = parent::doClean($value);

        $matches = array();

        if (preg_match('/^(\d+):(\d+)$/', $clean, $matches)) {
            $timeInMinutes = intval($matches[1])*60+intval($matches[2]);
            return $timeInMinutes;
        }

        $matches = array();

        if (preg_match('/^(\d+) ?[hH] ?[,.]? ?(\d+) ?[mM]?$/', $clean, $matches)) {
            $timeInMinutes = intval($matches[1])*60+intval($matches[2]);
            return $timeInMinutes;
        }
        
        $matches = array();

        if (preg_match('/^(\d+) ?[hH]$/', $clean, $matches)) {
            $timeInMinutes = intval($matches[1])*60;
            return $timeInMinutes;
        }

        $matches = array();

        if (preg_match('/^(\d+) ?[mM]$/', $clean, $matches)) {
            $timeInMinutes = intval($matches[1]);
            return $timeInMinutes;
        }

        $matches = array();

        if (preg_match('/^(\d+[.,]\d+) ?[hH]/', $clean, $matches)) {
            $float = str_replace(',', '.', $matches[1]);
            $timeInMinutes = intval(floatval($float)*60);
            return $timeInMinutes;
        }

        $matches = array();

        if (preg_match('/^(\d+(?:[.,]\d+)?) ?v\\.? ?h\\.?$/', $clean, $matches)) {
            $float = str_replace(',', '.', $matches[1]);
            $timeInMinutes = intval(floatval($float)*45);
            return $timeInMinutes;
        }


        throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }


}