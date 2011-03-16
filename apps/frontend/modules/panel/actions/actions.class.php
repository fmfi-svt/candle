<?php

/**

    Copyright 2011 Martin Sucha

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


class panelActions extends sfActions {

    public function executeListLessons(sfWebRequest $request) {

        $this->lessonForm = new LessonSearchForm();
        $queryString = $request->getParameter('showLessons');
        $this->lessonForm->bind(
            array('showLessons' => $queryString),
            array()
          );

        if ($this->lessonForm->isValid()) {
            $this->getUser()->setAttribute('panel_lesson_query', $queryString);
            if (empty($queryString)) {
                $this->subjects = null;
            }
            else {
                $this->subjects = Doctrine::getTable('Subject')->searchSubjectsByAll($queryString);
            }
        }
        else {
            $this->subjects = null;
        }

        $this->manager = $this->getUser()->getTimetableManager();
        $this->timetable_id = $request->getParameter('timetable_id');
        if ($this->timetable_id != null) {
            $this->timetable_id = intval($this->timetable_id);
            $this->timetable = $this->manager->getTimetable($this->timetable_id);
            $this->forward404Unless($this->timetable);
        }
        else {
            $this->timetable = null;
        }

        $this->setLayout(false);

    }

}