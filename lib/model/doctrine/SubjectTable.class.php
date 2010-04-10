<?php

class SubjectTable extends Doctrine_Table
{
    public function getActiveSubjects(Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()
                ->from('Subject s');
        }
        
        return $q->execute();
    }
    
    public function searchSubjectsByAll($queryString) {
        $q = Doctrine_Query::create()
            ->from('Subject s')
            ->innerJoin('s.Lessons l')
            ->leftJoin('l.Teacher t')
            ->leftJoin('l.LessonType ty')
            ->leftJoin('l.Room r')
            ->andWhere('s.name LIKE ?', '%'.$queryString.'%');
        return $q->execute();
    }
    
}
