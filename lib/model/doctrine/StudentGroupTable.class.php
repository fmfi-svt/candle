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

class StudentGroupTable extends Doctrine_Table
{

    public function searchStudentGroupsByName($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(' ', '%', $queryString).'%';

        $q = Doctrine_Query::create()
                ->from('StudentGroup s')
                ->where('s.name LIKE ?', $matchQueryString)
                ->orderBy('s.name')
                ->limit($limit);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
