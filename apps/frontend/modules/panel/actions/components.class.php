<?php

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
        
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->timetable_id)) $this->timetable_id = null;
    }
    
}
