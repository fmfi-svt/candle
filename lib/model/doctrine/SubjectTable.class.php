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
            ->select('s.id, s.name, s.short_code, l.id, l.day, l.start, t.given_name, t.family_name, ty.code, ty.name, r.name')
            ->from('Subject s')
            ->innerJoin('s.Lessons l')
            ->innerJoin('l.LessonType ty')
            ->innerJoin('l.Room r')
            ->leftJoin('l.Teacher t');
        
        if (strlen($queryString)>2) {
            $q->andWhere('s.name LIKE ?', $matchQueryString)
                ->orWhere('s.code LIKE ?', $matchQueryString)
                ->orWhere('EXISTS (SELECT st2.id FROM Teacher st2 LEFT JOIN st2.Lesson sl2 WHERE CONCAT(st2.given_name,\' \',st2.family_name) LIKE ? AND sl2.subject_id=s.id)', $matchQueryString);
        }
        
        $q->orWhere('EXISTS (SELECT sl.id FROM Lesson sl LEFT JOIN sl.Room sr WHERE sr.name LIKE ? AND sl.subject_id=s.id)', $matchQueryString)
            ->orderBy('s.name')
            ->limit($limit);
        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
}
