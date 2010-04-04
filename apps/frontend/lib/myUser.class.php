<?php

class myUser extends sfBasicSecurityUser
{

    public function getTimetableManager() {
        $manager = $this->getAttribute('timetable_manager', null);
        if ($manager == null) {
            $manager = new EditableTimetableManager();
            $this->setAttribute('timetable_manager', $manager);
        }
        return $manager;
    }

}
