<?php

/**

    Copyright 2010 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */

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
            ->select('s.id, s.name, s.short_code, s.rozsah, l.id, l.day, l.start, t.given_name, t.family_name, ty.code, ty.name, r.name')
            ->from('Subject s')
            ->innerJoin('s.Lessons l')
            ->innerJoin('l.LessonType ty')
            ->innerJoin('l.Room r')
            ->leftJoin('l.Teacher t');
        
        if (strlen($queryString)>2) {
            $q->andWhere('s.name LIKE ?', $matchQueryString)
                ->orWhere('s.code LIKE ?', $matchQueryString)
                ->orWhere('EXISTS (SELECT st2.id FROM Teacher st2 LEFT JOIN st2.Lesson sl2 WHERE ((CONCAT(st2.given_name,\' \',st2.family_name) LIKE ?) OR (CONCAT(st2.family_name,\' \',st2.given_name) LIKE ?)) AND sl2.subject_id=s.id)', array($matchQueryString, $matchQueryString));
        }
        
        $q->orWhere('EXISTS (SELECT sl.id FROM Lesson sl LEFT JOIN sl.Room sr WHERE sr.name LIKE ? AND sl.subject_id=s.id)', $matchQueryString)
            ->orderBy('s.name')
            ->limit($limit);
        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
}
