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
            ->andWhere('s.name LIKE ?', '%'.$queryString.'%');
            /*->union()
            ->from('Subject s1')
            ->innerJoin('s1.Lesson l')
            ->innerJoin('l.TeacherLessons lt')
            ->innerJoin('lt.Teacher t')
            ->andWhere('t.name LIKE ?', $queryString);*/
        return $q->execute();
    }
}
