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
                ->select('l.id, l.day, l.start, l.end, s.name, r.name, t.name, t.code')
                ->from('Lesson l')
                ->innerJoin('l.Subject s')
                ->innerJoin('l.Room r')
                ->innerJoin('l.LessonType t')
                ->andWhereIn('l.id', $lessonIdentifiers);
//                ->andWhere('l.id IN ()');

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

}
