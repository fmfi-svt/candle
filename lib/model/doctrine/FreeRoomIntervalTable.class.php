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

class FreeRoomIntervalTable extends Doctrine_Table
{

    protected function addInterval($room_id, $day, $start, $end) {
        $interval = new FreeRoomInterval();
        $interval['room_id'] = $room_id;
        $interval['day'] = $day;
        $interval['start'] = $start;
        $interval['end'] = $end;
        $interval->save();
    }

    /**
     * Calculate and insert all free room intervals into table,
     * so it can be later searched very quickly
     */
    public function calculate() {

        // delete old data
        $this->createQuery()
                ->delete()
                ->execute();

        $roomsAndLessons = Doctrine_Query::create()
                ->select('r.id, l.day, l.start, l.end')
                ->from('Room r')
                ->leftJoin('r.Lesson l')
                ->orderBy('r.id, l.day, l.start, l.end')
                ->execute(null, Doctrine::HYDRATE_ON_DEMAND);

        // We need to have at least one interval per room per day
        foreach ($roomsAndLessons as $room) {
            $day = 0; // day index in week, starting monday
            $time = 0; // time in minutes since start of day

            $room_id = $room['id'];

            foreach ($room['Lesson'] as $lesson) {
                while ($day < $lesson['day']) {
                    // end current day and move to the next
                    $this->addInterval($room_id, $day, $time, 24*60);
                    $day++;
                    $time = 0;
                }
                assert($day == $lesson['day']);
                // add interval before this lesson, if it wouldn't be zero
                if ($time < $lesson['start']) {
                    $this->addInterval($room_id, $day, $time, $lesson['start']);
                }
                // jump at the end of current lesson
                $time = $lesson['end'];
            }

            // finish whole week
            while ($day < 5) {
                // end current day and move to the next
                $this->addInterval($room_id, $day, $time, 24*60);
                $day++;
                $time = 0;
            }
        }

    }

    /**
     * Find valid matching intervals
     * @param int $minLength Minimum interval length in minutes
     * @param array $inIntervals array of array(day, start, end)
     * @param int $minKapacitaMiestnosti minimum room capacity
     */
    public function findIntervals($minLength, array $inIntervals, $minKapacitaMiestnosti=0, $externe=false, $seminarne=false, $prakticke=false) {

        // Fix a bug with bad SQL syntax on empty intervals
        if (count($inIntervals) == 0) return array();

        $q = Doctrine_Query::create();

        $q->from('FreeRoomInterval f')
                ->innerJoin('f.Room r')
                ->leftJoin('r.RoomType t')
                ->where('f.end - f.start >= ?', $minLength);

        $params = array();
        $intervalWhere = '(('; //DQL
        $firstInterval = true;

        foreach ($inIntervals as $interval) {
            if (!$firstInterval) {
                $intervalWhere .= ') OR (';
            }
            $firstInterval = false;

            $intervalWhere .= 'f.day = ? AND f.start <= ? AND f.end >= ?';
            $params[] = $interval[0]; //day
            $params[] = $interval[2]-$minLength; // end-minLength
            $params[] = $interval[1]+$minLength; // start+minLength
        }

        $intervalWhere .= '))';

        $q->andWhere($intervalWhere, $params);

        $q->andWhere('r.capacity >= ?', $minKapacitaMiestnosti);
        
        $q->andWhere('t.code != ?', '0');

        if (!$seminarne) {
            $q->andWhere('t.code != ?', 's');
        }

        if (!$externe) {
            $q->andWhere('t.code != ?', 'e');
        }
        
        if (!$prakticke) {
            $q->andWhere('t.code != ?', 'l');
        }

        return $q->execute(null, Doctrine::HYDRATE_ARRAY);
    }

}
