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

class timetableTeacherActions extends sfActions {

    private function fetchTeacherTimetable() {
        $teacher = $this->getRoute()->getObject();
        $this->forward404Unless($teacher);
        $this->timetable = new EditableTimetable();
        $lessons = Doctrine::getTable('Lesson')->fetchTeacherLessonIds($teacher['id']);
        foreach($lessons as $lesson) {
            $this->timetable->addLessonById($lesson['id']);
        }
        $this->teacher = $teacher;
        $this->teacher_id = $teacher['id'];
        $this->timetable->setName(Candle::formatShortName($teacher));
    }

    public function executeShow(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            Candle::setTimetableExportResponse($request, $this);
        }
    }

    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
    }
    
    public function executeList(sfWebRequest $request) {
        $this->groups = Candle::groupSorted(Doctrine::getTable('Teacher')->findAllSortedByName(), 'family_name');
    }


}
