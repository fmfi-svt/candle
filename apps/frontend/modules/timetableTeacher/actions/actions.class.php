<?php

class timetableTeacherActions extends sfActions {

    private function fetchTeacherTimetable() {
        $teacher = $this->getRoute()->getObject();
        $this->forward404Unless($teacher);
        $this->timetable = new EditableTimetable();
        $lessons = Doctrine::getTable('Lesson')->fetchTeacherLessonIds($teacher['id']);
        foreach($lessons as $lesson) {
            $this->timetable->addLessonById($lesson['id']);
        }
        $this->teacher = $teacher;
        $this->teacher_id = $teacher['id'];
        $this->timetable->setName(Candle::formatShortName($teacher));
    }

    public function executeShow(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
        $this->layout = new TimetableLayout($this->timetable->getLessons());
        if ($request->getRequestFormat() != 'html') {
            $this->setTemplate('show', 'timetable');
            Candle::setTimetableExportResponse($request, $this);
        }
    }

    public function executeDuplicate(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
        $manager = $this->getUser()->getTimetableManager();
        $newId = $manager->duplicateTimetable($this->timetable);
        $this->redirect('@timetable_show?id='.$newId);
    }

    public function executeExport(sfWebRequest $request) {
        $this->fetchTeacherTimetable();
    }


}
