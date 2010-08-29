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

class timetablePublishedActions extends sfActions {

    private function fetchPublishedTimetable() {
        $userTimetable = $this->getRoute()->getObject();
        $this->forward404Unless($userTimetable);
        $this->forward404Unless($userTimetable['published']); // TODO presunut do modelu route
        $this->timetable = new EditableTimetable();
        $this->timetable->load($userTimetable);
        $this->timetable_slug = $userTimetable['slug'];
    }

    public function executeShow(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            Candle::setTimetableExportResponse($request, $this);
        }
    }

    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchPublishedTimetable();
    }


}
