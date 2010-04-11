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
    
    public function searchSubjectsByAll($queryString, $limit = 50) {
        $matchQueryString = '%'.str_replace(array(' ', '.'), '%', $queryString).'%';
        
        $q = Doctrine_Query::create()
            ->from('Subject s')
            ->innerJoin('s.Lessons l')
            ->leftJoin('l.Teacher t')
            ->leftJoin('l.LessonType ty')
            ->leftJoin('l.Room r');
        
        if (strlen($queryString)>2) {
            $q->andWhere('s.name LIKE ?', $matchQueryString)
                ->orWhere('s.code LIKE ?', $matchQueryString)
                ->orWhere('EXISTS (SELECT st2.id FROM Teacher st2 LEFT JOIN st2.Lesson sl2 WHERE CONCAT(st2.given_name,\' \',st2.family_name) LIKE ? AND sl2.subject_id=s.id)', $matchQueryString);
        }
        
        $q->orWhere('EXISTS (SELECT sl.id FROM Lesson sl LEFT JOIN sl.Room sr WHERE sr.name LIKE ? AND sl.subject_id=s.id)', $matchQueryString)
            ->orderBy('s.name')
            ->limit($limit);
        return $q->execute();
    }
    
}
