<?php

class timetableComponents extends sfComponents {

    public function executeTop(sfWebRequest $request) {
        $this->all_timetables = $this->getUser()->getTimetableManager()->getTimetables();
        if (!isset($this->timetable)) $this->timetable = null;
        if (!isset($this->timetable_id)) $this->timetable_id = null;
        if (!isset($this->newSelected)) $this->newSelected = false;
    }

    public function executeEditMenu(sfWebRequest $request) {
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
    }

}