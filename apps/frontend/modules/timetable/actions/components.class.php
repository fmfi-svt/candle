<?php

class timetableComponents extends sfComponents {
    public function executePanel(sfWebRequest $request) {
        $this->lessonForm = new LessonSearchForm();
        $queryString = $request->getParameter('showLessons');
        $this->lessonForm->bind(
            array('showLessons' => $queryString),
            array()
          );

        if ($this->lessonForm->isValid()) {
            $this->subjects = Doctrine::getTable('Subject')->searchSubjectsByAll($queryString);
        }
        else {
            $this->subjects = null;
        }
    }
    
    public function executeTop(sfWebRequest $request) {
        $this->all_timetables = $this->getUser()->getTimetableManager()->getTimetables();
    }
}
