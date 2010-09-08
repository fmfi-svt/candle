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
 * Utility class for CSV output
 */


class CSV {

    /**
     * Escapni CSV field ak treba (podla RFC 4180)
     * referencia: http://tools.ietf.org/html/rfc4180
     *
     * @param string $field obsah policka
     * @param string $delimiter oddelovac policok
     * @param string $enclosure escapovacia obalka
     * @return string
     */
    public static function esc($field, $delimiter=',', $enclosure='"') {
        $needs_escape = strlen($field) == 0;
        $needs_escape = $needs_escape || (strpos($field, $delimiter)!== false);
        $needs_escape = $needs_escape || (strpos($field, $enclosure)!== false);
        $needs_escape = $needs_escape || (strpos($field, "\n")!== false);
        $needs_escape = $needs_escape || (strpos($field, "\r")!== false);
        if (!$needs_escape) {
            return $field;
        }
        return $enclosure.str_replace($enclosure, $enclosure.$enclosure, $field).$enclosure;
    }

}