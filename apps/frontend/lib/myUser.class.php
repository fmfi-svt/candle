<?php

class myUser extends sfGuardSecurityUser
{

    /**
     * Create or return timetable manager
     * @return EditableTimetableManager user's timetable manager
     */
    public function getTimetableManager() {
        $manager = $this->getAttribute('timetable_manager', null);
        if ($manager == null) {
            $manager = $this->initDefaultTimetableManager();            
        }
        return $manager;
    }

    public function setTimetableManager(EditableTimetableManager $manager) {
        $this->setAttribute('timetable_manager', $manager);
    }

    private function initDefaultTimetableManager() {
        $manager = new EditableTimetableManager();
        $manager->addDefaultTimetable();
        $this->setTimetableManager($manager);
        return $manager;
    }

    /**
    * Signs in the user on the application.
    *
    * @param sfGuardUser $user The sfGuardUser id
    * @param boolean $remember Whether or not to remember the user
    * @param Doctrine_Connection $con A Doctrine_Connection object
    */
    public function signIn($user, $remember = false, $con = null) {
        parent::signIn($user, $remember, $con);

        // Kedze nechcem, aby neulozene rozvrhy zo session ovplyvnovali tie
        // co uz ma uzivatel ulozene, vsetky doterajsie premenujem ak existuje
        // nejaky ulozeny s danym menom

        $oldTimetableManager = $this->getTimetableManager();

        $newTimetableManager = new EditableTimetableManager();

        $userTimetables = Doctrine::getTable('UserTimetable')->findWithLessonsForUserId($user->getId());
        foreach ($userTimetables as $userTimetable) {
            $timetable = new EditableTimetable();
            $timetable->load($userTimetable);
            $newTimetableManager->addTimetable($timetable);
        }

        // naimportujem vsetky doteraz otvorene a upravene rozvrhy do noveho managera
        foreach ($oldTimetableManager->getTimetables() as $timetable) {
            if ($timetable->isModified()) {
                $newTimetableManager->addTimetable($timetable);
            }
        }

        $this->setTimetableManager($newTimetableManager);

    }

    public function signOut() {
        parent::signOut();

        // zrusim vsetky otvorene rozvrhy
        $this->initDefaultTimetableManager();
    }

}
