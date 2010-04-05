<?php

class timetableActions extends sfActions {
    
    public function executeShow(sfWebRequest $request) {
        $manager = $this->getUser()->getTimetableManager();
        $id = $request->getParameter('id');
        $timetable = $manager->getTimetable($id);
        $this->forward404Unless($timetable);
        $this->timetable = $timetable;
        $this->timetable_id = $id;
        $this->layout = new TimetableLayout($timetable);
    }
    
    public function executeNew(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
    }
    
    public function executeCreate(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
        $this->processForm($this->form, $request);
        if ($this->form->isValid()) {
            $newTimetable = new EditableTimetable();
            $newTimetable->setName($request->getParameter('name'));
            $this->getUser()->getTimetableManager()->addTimetable($newTimetable);
        }
    }
    
    public function processForm(EditableTimetableForm $form, sfWebRequest $request) {
        $form->bind(array(
            'name' => $request->getParameter('name'),
            '_csrf_token' => $request->getParameter('_csrf_token')
        ));
    }
    
    public function executeChangeLessons(sfWebRequest $request) {
        $manager = $this->getUser()->getTimetableManager();
        $id = $request->getParameter('id');
        $timetable = $manager->getTimetable($id);
        $this->forward404Unless($timetable);
        
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
                $timetable->addLessonById($lesid);
            }
        }
        
        foreach ($lessonBefore as $lesid) {
            $now = isset($lessonSet[$lesid]);
            if (!$now) {
                $timetable->removeLessonById($lesid);
            }
        }

        foreach ($subject as $subid) {
            $before = isset($subjectBeforeSet[$subid]);
            if (!$before) {
                $timetable->addSubjectById($subid);
            }
        }
        
        foreach ($subjectBefore as $subid) {
            $now = isset($subjectSet[$subid]);
            if (!$now) {
                $timetable->removeSubjectById($subid);
            }
        }

        $this->redirect('@timetable_show?id='.$id);
    }

}
