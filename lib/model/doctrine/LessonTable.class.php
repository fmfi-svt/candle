<?php

class LessonTable extends Doctrine_Table
{

    function listBySubjectId($subjectId) {
        $q = Doctrine_Query::create()
                ->from('Lesson l')
                ->andWhere('l.subject_id = ?', $subjectId);
        return $q->execute();
    }
    
    function findFullById($lessonId) {
        $q = Doctrine_Query::create()
                ->from('Lesson l')
                ->leftJoin('l.Subject s')
                ->leftJoin('l.Room r')
                ->leftJoin('l.LessonType t')
                ->andWhere('l.id = ?', $lessonId);
        
        return $q->fetchOne();
    }
    
    function fetchFullLessons($lessonIdentifiers) {
        if (count($lessonIdentifiers) == 0) return array(); // inac to robi strasne haluze

        $q = Doctrine_Query::create()
                ->select('l.id, l.day, l.start, l.end, s.name, r.name, t.name, t.code, tt.given_name, tt.family_name')
                ->from('Lesson l')
                ->innerJoin('l.Subject s')
                ->innerJoin('l.Room r')
                ->innerJoin('l.LessonType t')
                ->leftJoin('l.Teacher tt')
                ->andWhereIn('l.id', $lessonIdentifiers)
                ->orderBy('l.day, l.start');

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

    function fetchTeacherLessonIds($teacherId) {
        $q = Doctrine_Query::create()
                ->select('l.id')
                ->from('Lesson l')
                ->innerJoin('l.Teacher tt')
                ->andWhere('tt.id = ?', $teacherId);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

    function fetchRoomLessonIds($roomId) {
        $q = Doctrine_Query::create()
                ->select('l.id')
                ->from('Lesson l')
                ->innerJoin('l.Room r')
                ->andWhere('r.id = ?', $roomId);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }


    function getIDsBySubjectCodes($codes) {
        if (count($codes) == 0) return array();

        $q = Doctrine_Query::create()
                ->select('l.id')
                ->from('Lesson l')
                ->innerJoin('l.Subject s')
                ->andWhereIn('s.short_code', $codes);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
