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
class panelComponents extends sfComponents {
    public function executePanel(sfWebRequest $request) {
        $this->lessonForm = new LessonSearchForm();
        $queryString = $this->getUser()->getAttribute('panel_lesson_query');
        $queryString = $request->getParameter('showLessons', $queryString);
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

        $this->teacherForm = new TeacherSearchForm();

        $queryString = $this->getUser()->getAttribute('panel_teacher_query');
        $queryString = $request->getParameter('showTeachers', $queryString);
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
            }
        }
        else {
            $this->teachers = null;
        }

        $this->roomForm = new RoomSearchForm();

        $queryString = $this->getUser()->getAttribute('panel_room_query');
        $queryString = $request->getParameter('showRooms', $queryString);
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
            }
        }
        else {
            $this->rooms = null;
        }

        $this->studentGroupForm = new StudentGroupSearchForm();

        $queryString = $this->getUser()->getAttribute('panel_studentGroup_query');
        $queryString = $request->getParameter('showStudentGroups', $queryString);
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
            }
        }
        else {
            $this->studentGroups = null;
        }
        
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->timetable_id)) $this->timetable_id = null;
    }
    
}
