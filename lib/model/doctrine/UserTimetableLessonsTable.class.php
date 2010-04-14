<?php

class UserTimetableLessonsTable extends Doctrine_Table
{
    public function deleteForUserTimetableId($userTimetableId) {
        $q = Doctrine_Query::create()
                ->delete()
                ->from('UserTimetableLessons ul')
                ->where('ul.user_timetable_id = ?', $userTimetableId);

        return $q->execute();
    }
}
