<?php

class myUser extends sfGuardSecurityUser
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
