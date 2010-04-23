<?php

class timetableActions extends sfActions {
    
    public function executeShow(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $this->layout = new TimetableLayout($this->timetable->getLessons());

        switch ($request->getRequestFormat())
        {
            case 'csv':
                $this->setLayout(false);
                $this->getResponse()->setContentType('text/csv;header=present'); // vid RFC 4180
                break;
            case 'ics':
                $this->setLayout(false);
                $this->getResponse()->setContentType('text/calendar'); // vid RFC 2445
                break;
        }

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
        $this->setTemplate('new');
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
        $lessonHighlightedBefore = $request->getParameter('lessonHighlightedBefore', array());
        $lessonHighlighted = $request->getParameter('lessonHighlighted', array());
        
        $subjectBeforeSet = array();
        $subjectSet = array();
        $lessonBeforeSet = array();
        $lessonSet = array();
        $lessonHighlightedBeforeSet = array();
        $lessonHighlightedSet = array();
        
        foreach ($subjectBefore as $subid) {
            $subjectBeforeSet[$subid] = true;
        }
        
        foreach ($lessonBefore as $lesid) {
            $lessonBeforeSet[$lesid] = true;
        }

        foreach ($lessonHighlightedBefore as $lesid) {
            $lessonHighlightedBeforeSet[$lesid] = true;
        }
        
        foreach ($subject as $subid) {
            $subjectSet[$subid] = true;
        }
        
        foreach ($lesson as $lesid) {
            $lessonSet[$lesid] = true;
        }

        foreach ($lessonHighlighted as $lesid) {
            $lessonHighlightedSet[$lesid] = true;
        }
        
        foreach ($lesson as $lesid) {
            $before = isset($lessonBeforeSet[$lesid]);
            if (!$before) {
                $this->timetable->addLessonById($lesid);
            }
        }

        foreach ($lessonHighlighted as $lesid) {
            $before = isset($lessonHighlightedBeforeSet[$lesid]);
            if (!$before) {
                $this->timetable->highlightLessonById($lesid);
            }
        }

        foreach ($lessonHighlightedBefore as $lesid) {
            $now = isset($lessonHighlightedSet[$lesid]);
            if (!$now) {
                $this->timetable->unhighlightLessonById($lesid);
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

    public function executeSave(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $user = $this->getUser()->getGuardUser();
        if (!$user) {
            $this->redirect('@sf_guard_signin');
            return;
        }
        $this->timetable->save($user->getId());
        $this->getUser()->setFlash('notice', 'Rozvrh úspešne uložený');
        $this->redirect('@timetable_show?id='.$this->timetable_id);
    }

    public function executePublishExecute(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $user = $this->getUser()->getGuardUser();
        $this->publishCheckLogin();

        $this->form = new EditableTimetablePublishForm();
        $this->form->bind(array(
            'slug' => $request->getParameter('slug'),
            '_csrf_token' => $request->getParameter('_csrf_token')
        ));

        if ($this->form->isValid()) {
            if ($this->timetable->isModified()) {
                $this->timetable->save($user->getId());
            }
            $userTimetable = Doctrine::getTable('UserTimetable')->find($this->timetable->getUserTimetableId());

            $userTimetable['slug'] = $request->getParameter('slug');
            $userTimetable['published'] = true;

            try {
                $userTimetable->save();
            }
            catch (Doctrine_Connection_Exception $e) {
                if ($e->getPortableCode() == -5) {
                    $this->getUser()->setFlash('error', 'Takýto rozvrh už existuje');
                    $this->setTemplate('publish');
                    return;
                }
                throw $e;
                return;
            }

            $this->getUser()->setFlash('notice', 'Rozvrh úspešne publikovaný');
            $this->redirect('@timetable_show?id='.$this->timetable_id);
        }
        $this->setTemplate('publish');
    }

    public function executeDelete(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $this->manager->removeTimetable($this->timetable_id);
        $this->getUser()->setFlash('notice', 'Rozvrh úspešne zmazaný');
        $this->redirect('@timetable_show?id='.$this->manager->getFirstTimetableId());
    }

    public function executeHomepage(sfWebRequest $request) {
        $timetableId = $this->getUser()->getTimetableManager()->getFirstTimetableId();
        $request->setParameter('id', $timetableId);
        $this->forward('timetable', 'show');
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchTimetable($request);
    }

    public function executePublish(sfWebRequest $request) {
        $this->publishCheckLogin();
        $this->fetchTimetable($request);
        $this->form = new EditableTimetablePublishForm();
    }

    private function publishCheckLogin() {
        $user = $this->getUser()->getGuardUser();
        if (!$user) {
            $this->getUser()->setFlash('error', 'Pre publikovanie rozvrhu musíte byť prihlásený/á');
            $this->redirect('@sf_guard_signin');
            return;
        }
    }

}
