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

    public function deleteById($userTimetableId) {
        $q = Doctrine_Query::create()
                ->delete()
                ->from('UserTimetable t')
                ->where('t.id = ?', $userTimetableId);

        return $q->execute();
    }

}
