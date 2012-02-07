<?php

/**

    Copyright 2010, 2011, 2012 Martin Sucha

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
                ->select('l.id, l.day, l.start, l.end, l.note, s.name, s.short_code, r.name, t.name, t.code, tt.given_name, tt.family_name')
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

    function fetchStudentGroupLessonIds($studentGroupId) {
        $q = Doctrine_Query::create()
                ->select('l.id')
                ->from('Lesson l')
                ->innerJoin('l.StudentGroup sg')
                ->andWhere('sg.id = ?', $studentGroupId);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }


    function getIDsBySubjectCodes($codes) {
        $shortCodes = array();
        foreach ($codes as $code) {
          $shortCode = Candle::subjectShortCode($code);
          if ($shortCode !== false) {
              $shortCodes[] = $shortCode;
          }
        }

        if (count($shortCodes) == 0) return array();

        $q = Doctrine_Query::create()
                ->select('l.id')
                ->from('Lesson l')
                ->innerJoin('l.Subject s')
                ->andWhereIn('s.short_code', $shortCodes);

        return $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

    /**
     * Find lessons in time intervals
     * @param array $inIntervals array of array(day, start, end)
     */
    public function findIntervals(array $inIntervals, $broadMatch = false, $orderBySubjectName = false) {

        // Fix a bug with bad SQL syntax on empty intervals
        if (count($inIntervals) == 0) return array();

        $q = Doctrine_Query::create();

        $q->from('Lesson l')
                ->innerJoin('l.Room r')
                ->leftJoin('l.Subject s')
                ->leftJoin('l.Teacher t')
                ->leftJoin('l.LessonType lt');
        if ($orderBySubjectName) {
            $q->orderBy('s.name, l.day, l.start, l.end, r.name');
        }
        else {
            $q->orderBy('l.day, l.start, l.end, r.name');
        }

        $params = array();
        $intervalWhere = '(('; //DQL
        $firstInterval = true;

        foreach ($inIntervals as $interval) {
            if (!$firstInterval) {
                $intervalWhere .= ') OR (';
            }
            $firstInterval = false;

            $params[] = $interval[0]; //day
            
            if ($broadMatch) {
                $intervalWhere .= 'l.day = ? AND l.start <= ? AND l.end >= ?';
                $params[] = $interval[2]; // end
                $params[] = $interval[1]; // start
            }
            else {
                $intervalWhere .= 'l.day = ? AND l.start >= ? AND l.end <= ?';
                $params[] = $interval[1]; // start
                $params[] = $interval[2]; // end
            }
        }

        $intervalWhere .= '))';

        $q->andWhere($intervalWhere, $params);

        return $q->execute(null, Doctrine::HYDRATE_ARRAY);
    }

}
