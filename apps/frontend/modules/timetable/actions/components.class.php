<?php

class timetableComponents extends sfComponents {
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
            $this->subjects = Doctrine::getTable('Subject')->searchSubjectsByAll($queryString);
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
            $this->teachers = Doctrine::getTable('Teacher')->searchTeachersByName($queryString);
        }
        else {
            $this->teachers = null;
        }
        
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->timetable_id)) $this->timetable_id = null;
    }
    
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
