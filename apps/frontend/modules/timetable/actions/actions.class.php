<?php

/**

    Copyright 2010,2011 Martin Sucha

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

class timetableActions extends sfActions {

    public function executeShow(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->isXmlHttpRequest() || $request->hasParameter('onlyTimetable')) {
            $this->setLayout(false);
            $this->addSlots = false;
        }
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
        Candle::setTimetableExportResponse($request, $this);
        header('Expires: Mon, 1 Jan 1990 00:00:00 GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        $changeToken = sha1(time()."__".mt_rand());
        $this->getResponse()->setCookie('timetableEditor_changeToken',
                $changeToken, null, $this->getController()->genUrl('@homepage'),
                $request->getHost());
        $this->changeToken = $changeToken;
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
    }

    public function executeRename(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $this->form = new EditableTimetableForm();
        $this->form->setDefault('name', $this->timetable->getName());
    }

    public function executeRenameExecute(sfWebRequest $request) {
        $this->form = new EditableTimetableForm();
        $this->fetchTimetable($request);
        $this->processForm($this->form, $request);
        if ($this->form->isValid()) {
            $this->timetable->setName($request->getParameter('name'));
            $this->timetable->markModified();
            $this->redirect(array('sf_route'=>'timetable_show', 'id'=>$this->timetable_id));
        }
        $this->setTemplate('rename');
    }
    
    private function fetchTimetable(sfWebRequest $request) {
        $this->manager = $this->getUser()->getTimetableManager();
        $this->timetable_id = $request->getParameter('id');
        $this->timetable = $this->manager->getTimetable($this->timetable_id);
        $this->forward404Unless($this->timetable);
        $this->addSlots = true;
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

    private function getAffectedLessons(sfWebRequest $request, EditableTimetable $timetable) {
        $affectedLessons = null;
        $selectionSource = $request->getParameter('selection_source');
        if ($selectionSource == 'selection' || $selectionSource == 'selection_inv') {
            $affectedLessons = array();
            $lessonSelection = $request->getParameter('lesson_selection',array());
            foreach ($lessonSelection as $lessonId) {
                if ($timetable->hasLesson(intval($lessonId))) {
                    $affectedLessons[] = intval($lessonId);
                }
            }
        }
        else if ($selectionSource == 'highlight' || $selectionSource == 'highlight_inv') {
            $affectedLessons = $timetable->getHighlightedLessonIds();
        }
        else if ($selectionSource == 'all') {
            $affectedLessons = $timetable->getLessonIds();
        }
        else {
            throw new Exception("Unknown value of selection_source");
        }
        // Ak mame nejaky inverzny vyber, tak vybrate hodiny invertujme
        if ($selectionSource == 'selection_inv' || $selectionSource == 'highlight_inv') {
            $negativeLessons = $affectedLessons;
            $affectedLessons = array();
            foreach ($timetable->getLessonIds() as $lessonId) {
                // V nasledujucom riadku musi byt === false, pretoze
                // array_search vracia prislusny key, t.j. pre kluc 0
                // by obycajne porovnanie preslo aj ked nema
                if (array_search($lessonId, $negativeLessons) === false) {
                    $affectedLessons[] = $lessonId;
                }
            }
        }
        return $affectedLessons;
    }

    public function executeLessonAction(sfWebRequest $request) {
        $this->fetchTimetable($request);

        $lessons = $this->getAffectedLessons($request, $this->timetable);

        $lessonAction = $request->getParameter("selection_action");

        if ($lessonAction == 'remove') {
            foreach ($lessons as $lessonId) {
                $this->timetable->removeLessonById($lessonId);
            }
        }
        else if ($lessonAction == 'highlight') {
            foreach ($lessons as $lessonId) {
                $this->timetable->highlightLessonById($lessonId);
            }
        }
        else if ($lessonAction == 'unhighlight') {
            foreach ($lessons as $lessonId) {
                $this->timetable->unhighlightLessonById($lessonId);
            }
        }
        else {
            throw new Exception("Unknown value of selection_action");
        }

        if ($request->isXmlHttpRequest()) {
            return $this->renderText('ok');
        }
        $this->redirect('@timetable_show?id='.$this->timetable_id);
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
        if ($request->isXmlHttpRequest()) {
            return $this->renderText('ok');
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

        $this->layout = new TimetableLayout($this->timetable->getLessons());

        if ($this->form->isValid()) {
            if ($this->timetable->isModified() || $this->timetable->getUserTimetableId() == null) {
                $this->timetable->save($user->getId());
            }
            $userTimetable = Doctrine::getTable('UserTimetable')->find($this->timetable->getUserTimetableId(), Doctrine::HYDRATE_RECORD);

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

    public function executeUnpublishExecute(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $user = $this->getUser()->getGuardUser();
        $this->publishCheckLogin();
        $userTimetable = Doctrine::getTable('UserTimetable')->find($this->timetable->getUserTimetableId());
        $userTimetable['published'] = false;
        $userTimetable->save();
        $this->getUser()->setFlash('notice', 'Rozvrh už nie je verejne prístupný');
        $this->redirect('@timetable_show?id='.$this->timetable_id);
    }

    public function executeDelete(sfWebRequest $request) {
        $this->fetchTimetable($request);

        $this->form = new DeleteConfirmationForm();
        $this->form->bind($request->getParameter('deleteConfirmation'));

        if ($this->form->isValid()) {
            $this->manager->removeTimetable($this->timetable_id);
            $this->getUser()->setFlash('notice', 'Rozvrh úspešne zmazaný');
            $this->redirect(array('sf_route'=>'timetable_show', 'id'=>$this->manager->getFirstTimetableId()));
        }

        $this->layout = new TimetableLayout($this->timetable->getLessons());
        $this->setTemplate('deleteAsk');
    }

    public function executeDeleteAsk(sfWebRequest $request) {
        $this->form = new DeleteConfirmationForm();
        $this->fetchTimetable($request);
        $this->layout = new TimetableLayout($this->timetable->getLessons());
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
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        /*if ($this->timetable->isPersisted()) {
            $userTimetable = Doctrine::getTable('UserTimetable')->find($this->timetable->getUserTimetableId());
            $this->form['slug']->setValue($userTimetable['slug']);
        }*/
    }

    private function publishCheckLogin() {
        $user = $this->getUser()->getGuardUser();
        if (!$user) {
            $this->getUser()->setFlash('error', 'Pre publikovanie rozvrhu musíte byť prihlásený/á');
            $this->redirect('@sf_guard_signin');
            return;
        }
    }

    public function executeImport(sfWebRequest $request) {
        $this->fetchTimetable($request);
    }

    public function executeImportDo(sfWebRequest $request) {
        $this->fetchTimetable($request);
        $text = $request->getParameter('text');
        $text = str_replace(array("\r\n", "\n", "\r"), ",", $text);
        $text = str_replace(" ", "", $text);
        $codes = explode(",",$text);
        $lessons = Doctrine::getTable('Lesson')->getIDsBySubjectCodes($codes);
        if (count($lessons) != 0) {
            foreach ($lessons as $lesson) {
                $this->timetable->addLessonById($lesson['id']);
            }
            $this->getUser()->setFlash('notice', 'Dáta naimportované');
            $this->redirect('@timetable_show?id='.$this->timetable_id);
        }
        else {
            $this->getUser()->setFlash('error', 'Nenašli sa žiadne hodiny');
            $this->redirect('@timetable_import?id='.$this->timetable_id);
        }
    }

}
