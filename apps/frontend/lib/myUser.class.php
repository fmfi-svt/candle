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

        $nextId = $oldTimetableManager->getMaxId()+1;

        $userTimetables = Doctrine::getTable('UserTimetable')->findWithLessonsForUserId($user->getId());
        foreach ($userTimetables as $userTimetable) {
            $timetable = new EditableTimetable();
            $timetable->load($userTimetable);
            $newTimetableManager->addTimetable($timetable, $nextId);
            $nextId++;
        }

        // naimportujem vsetky doteraz otvorene a upravene rozvrhy do noveho managera
        foreach ($oldTimetableManager->getTimetables() as $id=>$timetable) {
            if ($timetable->isModified()) {
                $newTimetableManager->addTimetable($timetable, $id);
            }
        }

        if ($newTimetableManager->isEmpty()) {
            $newTimetableManager->addDefaultTimetable();
        }

        $this->setTimetableManager($newTimetableManager);
    }

    public function signOut() {
        parent::signOut();

        $this->clearSession();
    }

    public function clearSession() {
        $this->getAttributeHolder()->clear();
    }

}
