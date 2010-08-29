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
 * Parser for rozsah string from AIS2
 */
class RozsahParser {

    /**
     *
     * @param string $rozsah (e.g. '2P + 3C')
     * @return array('P'=>2, 'C'=>3)
     */
    public function parse($rozsah) {
        $rozsah = str_replace(array(' ', "\t", "\n"), '', $rozsah);

        $subparts = explode('+', $rozsah);

        $result = array();

        foreach ($subparts as $subpart) {
            $matches = array();
            if (!preg_match('@^(\d+)([A-Z])$@', $subpart, $matches)) {
                throw new Exception('Invalid subpart in rozsah');
            }
            if (isset($result[$matches[2]])) {
                throw new Exception('Already existing subpart');
            }
            $result[$matches[2]] = intval($matches[1]);
        }
        
        return $result;
    }

}