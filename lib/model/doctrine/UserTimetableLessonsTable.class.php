<?php

class UserTimetableLessonsTable extends Doctrine_Table
{
    public function deleteForUserTimetableId($userTimetableId) {
        $q = Doctrine_Query::create()->delete();

        $q = $this->forUserTimetableId($userTimetableId, $q);

        return $q->execute();
    }

    public function forUserTimetableId($userTimetableId, Doctrine_Query $q = null) {
        if ($q == null) {
            $q = Doctrine_Query::create();
        }
        $q->from('UserTimetableLessons ul')
          ->where('ul.user_timetable_id = ?', $userTimetableId);
        return $q;
    }
}
