<?php

class timetableActions extends sfActions {
    
    public function executeShow(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $this->layout = new TimetableLayout($this->timetable);
    }
    
    public function executeNew(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
    }
    
    private function fetchTimetable(sfWebRequest $request) {
        $this->manager = $this->getUser()->getTimetableManager();
        $this->timetable_id = $request->getParameter('id');
        $this->timetable = $this->manager->getTimetable($this->timetable_id);
        $this->forward404Unless($this->timetable);
    }
    
    public function executeCreate(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
        $this->processForm($this->form, $request);
        if ($this->form->isValid()) {
            $newTimetable = new EditableTimetable();
            $newTimetable->setName($request->getParameter('name'));
            $newId = $this->getUser()->getTimetableManager()->addTimetable($newTimetable);
            $this->redirect('@timetable_show?id='.$newId);
        }
    }
    
    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $newId = $this->manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }
    
    public function processForm(EditableTimetableForm $form, sfWebRequest $request) {
        $form->bind(array(
            'name' => $request->getParameter('name'),
            '_csrf_token' => $request->getParameter('_csrf_token')
        ));
    }
    
    public function executeChangeLessons(sfWebRequest $request) {
        $this->fetchTimetable($request);
                
        // odstranim hodiny, ktore boli zaciarknute
        // a teraz nie su a naopak
        $subjectBefore = $request->getParameter('subjectBefore', array());
        $subject = $request->getParameter('subject', array());
        $lessonBefore = $request->getParameter('lessonBefore', array());
        $lesson = $request->getParameter('lesson', array());
        
        $subjectBeforeSet = array();
        $subjectSet = array();
        $lessonBeforeSet = array();
        $lessonSet = array();
        
        foreach ($subjectBefore as $subid) {
            $subjectBeforeSet[$subid] = true;
        }
        
        foreach ($lessonBefore as $lesid) {
            $lessonBeforeSet[$lesid] = true;
        }
        
        foreach ($subject as $subid) {
            $subjectSet[$subid] = true;
        }
        
        foreach ($lesson as $lesid) {
            $lessonSet[$lesid] = true;
        }
        
        foreach ($lesson as $lesid) {
            $before = isset($subjectLessonSet[$lesid]);
            if (!$before) {
                $this->timetable->addLessonById($lesid);
            }
        }
        
        foreach ($lessonBefore as $lesid) {
            $now = isset($lessonSet[$lesid]);
            if (!$now) {
                $this->timetable->removeLessonById($lesid);
            }
        }

        foreach ($subject as $subid) {
            $before = isset($subjectBeforeSet[$subid]);
            if (!$before) {
                $this->timetable->addSubjectById($subid);
            }
        }
        
        foreach ($subjectBefore as $subid) {
            $now = isset($subjectSet[$subid]);
            if (!$now) {
                $this->timetable->removeSubjectById($subid);
            }
        }

        $this->redirect('@timetable_show?id='.$this->timetable_id);
    }

}
