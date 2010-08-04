<?php

class roomActions extends sfActions {

    private function fetchRoomTimetable() {
        $room = $this->getRoute()->getObject();
        $this->forward404Unless($room);
        $this->timetable = new EditableTimetable();
        $lessons = Doctrine::getTable('Lesson')->fetchRoomLessonIds($room['id']);
        foreach($lessons as $lesson) {
            $this->timetable->addLessonById($lesson['id']);
        }
        $this->room = $room;
        $this->room_id = $room['id'];
        $this->timetable->setName($room['name']);
    }

    public function executeShow(sfWebRequest $request) {
        $this->fetchRoomTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            Candle::setTimetableExportResponse($request, $this);
        }
    }

    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchRoomTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchRoomTimetable();
    }


}
