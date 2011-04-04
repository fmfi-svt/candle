<?php

/**

    Copyright 2011 Martin Sucha
    Copyright 2011 Tomi Belan

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

    public function executeListTeachers(sfWebRequest $request) {

        $this->teacherForm = new TeacherSearchForm();
        $queryString = $request->getParameter('showTeachers');
        $this->teacherForm->bind(
            array('showTeachers' => $queryString),
            array()
        );

        if ($this->teacherForm->isValid()) {
            $this->getUser()->setAttribute('panel_teacher_query', $queryString);
            if (empty($queryString)) {
                $this->teachers = null;
            }
            else {
                $this->teachers = Doctrine::getTable('Teacher')->searchTeachersByName($queryString);
                // note: no automatic redirect to teacher on single result
            }
        }
        else {
            $this->teachers = null;
        }

        $this->setLayout(false);

    }

    public function executeListRooms(sfWebRequest $request) {

        $this->roomForm = new RoomSearchForm();
        $queryString = $request->getParameter('showRooms');
        $this->roomForm->bind(
            array('showRooms' => $queryString),
            array()
        );

        if ($this->roomForm->isValid()) {
            $this->getUser()->setAttribute('panel_room_query', $queryString);
            if (empty($queryString)) {
                $this->rooms = null;
            }
            else {
                $this->rooms = Doctrine::getTable('Room')->searchRoomsByName($queryString);
                // note: no automatic redirect to room on single result
            }
        }
        else {
            $this->rooms = null;
        }

        $this->setLayout(false);

    }

    public function executeListStudentGroups(sfWebRequest $request) {

        $this->studentGroupForm = new StudentGroupSearchForm();
        $queryString = $request->getParameter('showStudentGroups');
        $this->studentGroupForm->bind(
            array('showStudentGroups' => $queryString),
            array()
        );

        if ($this->studentGroupForm->isValid()) {
            $this->getUser()->setAttribute('panel_studentGroup_query', $queryString);
            if (empty($queryString)) {
                $this->studentGroups = null;
            }
            else {
                $this->studentGroups = Doctrine::getTable('StudentGroup')->searchStudentGroupsByName($queryString);
                // note: no automatic redirect to group on single result
            }
        }
        else {
            $this->studentGroups = null;
        }

        $this->setLayout(false);

    }

}
