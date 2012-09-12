<?php

/**

    Copyright 2010,2012 Martin Sucha

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

class TeacherTable extends Doctrine_Table
{

    public function searchTeachersByName($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(array(' ', '.'), '%', $queryString).'%';

        $q = Doctrine_Query::create()
                ->from('Teacher t')
                ->where('CONCAT(t.given_name,\' \',t.family_name) LIKE ?', $matchQueryString)
                ->orWhere('CONCAT(t.family_name,\' \',t.given_name) LIKE ?', $matchQueryString)
                ->orderBy('t.family_name')
                ->limit($limit);

        return $q->execute();
    }

    public function findAllSortedByName() {
        $q = Doctrine_Query::create()
                ->from('Teacher t')
                ->orderBy('CASE WHEN LEFT(t.family_name, 1) IN (\'\', \'.\') THEN 1 else -1 END ASC, t.family_name COLLATE utf8_slovak_ci ASC, t.given_name COLLATE utf8_slovak_ci ASC');
        return $q->execute();
    }

}
