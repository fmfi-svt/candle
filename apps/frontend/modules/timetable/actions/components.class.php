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
    }
    
    public function executeTop(sfWebRequest $request) {
        $this->all_timetables = $this->getUser()->getTimetableManager()->getTimetables();
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->newSelected)) $this->newSelected = false;
    }
}
