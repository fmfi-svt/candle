<?php

class UserTimetableTable extends Doctrine_Table
{

    public function findWithLessonsForUserId($userId) {
        $q = Doctrine_Query::create()
                ->from('UserTimetable t')
                ->leftJoin('t.UserTimetableLessons l')
                ->where('t.user_id = ?', $userId);

        return $q->execute();
    }

}
