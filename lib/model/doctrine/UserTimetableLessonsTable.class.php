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

class UserTimetableLessonsTable extends Doctrine_Table
{
    public function deleteForUserTimetableId($userTimetableId) {
        $q = Doctrine_Query::create()->delete();

        $q = $this->forUserTimetableId($userTimetableId, $q);

        return $q->execute();
    }

    public function forUserTimetableId($userTimetableId, Doctrine_Query $q = null) {
        if ($q == null) {
            $q = Doctrine_Query::create();
        }
        $q->from('UserTimetableLessons ul')
          ->where('ul.user_timetable_id = ?', $userTimetableId);
        return $q;
    }
}
