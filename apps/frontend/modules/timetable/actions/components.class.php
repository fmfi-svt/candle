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

class timetableComponents extends sfComponents {

    public function executeTop(sfWebRequest $request) {
        $this->all_timetables = $this->getUser()->getTimetableManager()->getTimetables();
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->timetable_id)) $this->timetable_id = null;
        if (!isset($this->newSelected)) $this->newSelected = false;
    }

    public function executeEditMenu(sfWebRequest $request) {
        if ($this->timetable->isPersisted()) {
            $userTimetable = Doctrine::getTable('UserTimetable')->find($this->timetable->getUserTimetableId());
            if ($userTimetable['published']) { // TODO presunut check do modelu
                $this->published_slug = $userTimetable['slug'];
            }
            else {
                $this->published_slug = null;
            }
        }
        else {
            $this->published_slug = null;
        }
    }

}