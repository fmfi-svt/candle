<?php

class roomActions extends sfActions {

    private function fetchStudentGroupTimetable() {
        $studentGroup = $this->getRoute()->getObject();
        $this->forward404Unless($studentGroup);
        $this->timetable = new EditableTimetable();
        $lessons = Doctrine::getTable('Lesson')->fetchStudentGroupLessonIds($studentGroup['id']);
        foreach($lessons as $lesson) {
            $this->timetable->addLessonById($lesson['id']);
        }
        $this->studentGroup = $studentGroup;
        $this->studentGroup_id = $studentGroup['id'];
        $this->timetable->setName($studentGroup['name']);
    }

    public function executeShow(sfWebRequest $request) {
        $this->fetchStudentGroupTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            Candle::setTimetableExportResponse($request, $this);
        }
    }

    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchStudentGroupTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchStudentGroupTimetable();
    }


}
