<?php

class timetablePublishedActions extends sfActions {

    private function setTimetableExportResponse(sfWebRequest $request) {
        $format = $request->getRequestFormat();

        switch ($format)
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

    private function fetchPublishedTimetable() {
        $userTimetable = $this->getRoute()->getObject();
        $this->forward404Unless($userTimetable);
        $this->forward404Unless($userTimetable['published']); // TODO presunut do modelu route
        $this->timetable = new EditableTimetable();
        $this->timetable->load($userTimetable);
        $this->timetable_slug = $userTimetable['slug'];
    }

    public function executeShowPublished(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            $this->setTimetableExportResponse($request);
        }
    }

    public function executeDuplicatePublished(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExportPublished(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
    }


}
