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
 * Class representing a simple day/time interval
 */

class TimeInterval {

    /**
     * start of the interval in minutes since start of the week
     * @var int
     */
    var $start;

    /**
     * end of the interval in minutes since start of the week
     * @var int
     */
    var $end;

    /**
     * @param $start start of the interval in minutes since start of the week
     * @param $end end of the interval in minutes since start of the week
     */
    public function __construct($start, $end) {
        if ($end < $start) throw new Exception("Invalid time interval");
        $this->start = $start;
        $this->end = $end;
    }


    /**
     * check if this interval contains the given time
     * @param int $time
     */
    public function contains($time) {
        return ($time >= $this->start) && ($time <= $this->end);
    }

    /**
     * check if this interval intersects the other
     * two intervals intersect if they share at least one common point in time
     * @param TimeInterval $other
     * @return boolean true if the intervals intersect
     */
    public function intersects(TimeInterval $other) {
        return ($other->getStart() <= $this->end) && ($other->getEnd() >= $this->start);
    }

    /**
     * return minimal interval covering area of this and the other interval
     * @param TimeInterval $other
     * @return TimeInterval
     */
    public function extend(TimeInterval $other) {
        return new TimeInterval(min($this->start, $other->getStart()), max($this->end, $other->getEnd()));
    }


    /**
     * Return a union of this and the other interval if they intersect,
     * else return null
     * @param TimeInterval $other
     * @return TimeInterval
     */
    public function union(TimeInterval $other) {
        if (!$this->intersects($other)) return null;
        return $this->extend($other);
    }

    public function getStart() {
        return $this->start;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getLength() {
        return $this->end-$this->start;
    }

    public function getStartDay() {
        return intval($this->start/1440);
    }

    public function getStartTime() {
        return $this->start%1440;
    }

    public function getEndDay() {
        return intval($this->end/1440);
    }

    public function getEndTime() {
        return $this->end%1440;
    }

    public function overlapsDay() {
        $startDay = $this->getStartDay();
        $endDay = $this->getEndDay();
        $endTime = $this->getEndTime();

        if ($endDay == $startDay) return false;
        if ($endDay == $startDay+1 && $endTime == 0) return false;
        return true;
    }

    public function isEmpty() {
        return $this->getLength() == 0;
    }

    /**
     * Intersect with a single interval, if the intervals
     * do not intersect, return an empty interval.
     * @param TimeInterval $other
     */
    public function intersect(TimeInterval $other) {

        $newStart = max($this->getStart(), $other->getStart());
        $newEnd = min($this->getEnd(), $other->getEnd());

        if ($newStart > $newEnd) {
            // TODO: maybe there should be one static
            // instance of zero length interval
            return new TimeInterval(0,0);
        }

        return new TimeInterval($newStart, $newEnd);
    }

    /**
     * Intersect this interval with an array of intervals,
     * producing an array containg result of intersection
     */
    public function intersectArray(array $more) {
        $result = array();
        foreach ($more as $other) {
            $intersection = $this->intersect($other);
            if (!$intersection->isEmpty()) {
                $result[] = $intersection;
            }
        }
        return $result;
    }

    public function toString() {
        $str = Candle::formatShortDay($this->getStartDay());
        $str .= ' ';
        $str .= Candle::formatTime($this->getStartTime());
        $str .= '-';
        if ($this->overlapsDay()) {
            $str .= Candle::formatShortDay($this->getEndDay());
            $str .= ' ';
        }
        $str .= Candle::formatTime($this->getEndTime());
        return $str;
    }

    public function __toString() {
        return $this->toString();
    }

    /**
     * Join any overlapping intervals in the array
     * @param array $intervals
     * @return array joined intervals
     */
    public static function mergeIntervals(array $intervals) {
        usort($intervals, array('TimeInterval', 'compareByStart'));
        $i = 0;
        while ($i < count($intervals)-1) {
            $a = $intervals[$i];
            $b = $intervals[$i+1];

            $u = $a->union($b);

            if ($u) {
                // replace the two with u
                array_splice($intervals, $i, 2, array($u));
            }
            else {
                $i++;
            }
        }
        return $intervals;
    }

    public static function splitIntervalsByDays(array $intervals) {
        $i = 0;
        while ($i < count($intervals)) {
            $a = $intervals[$i];
            if ($a->overlapsDay()) {
                $day = $a->getStartDay();
                $time = $a->getStartTime();
                $replacement = array();

                while ($day < $a->getEndDay()) {
                    $replacement[] = new TimeInterval($day*1440+$time,($day+1)*1440);
                    $day += 1;
                    $time = 0;
                }

                // day == $a->getEndDay()
                if ($a->getEndTime() > 0) {
                    // we append only non-empty intervals
                    $replacement[] = new TimeInterval($day*1440, $day*1440+$a->getEndTime());
                }

                array_splice($intervals, $i, 1, $replacement);
                $i += count($replacement)-1;
            }
            else {
                $i++;
            }
        }
        return $intervals;
    }

    public static function convertIntervalsToTriplesArray(array $intervals) {
        // We need intervals to be split by days
        // so interval with endTime()==0 ends at midnight
        $intervals = self::splitIntervalsByDays($intervals);

        $ret = array();
        foreach ($intervals as $interval) {
            $day = $interval->getStartDay();
            $start = $interval->getStartTime();
            $end = $interval->getEndTime();
            if ($end == 0) {
                // special case: interval ends at midnight
                // (and as such $interval->endDay()==$interval->startDay()+1)
                $end = 1440;
            }
            $ret[] = array($day, $start, $end);
        }

        return $ret;
    }

    public static function optimizeIntervals(array $intervals) {
        $intervals = self::mergeIntervals($intervals);
        return self::convertIntervalsToTriplesArray($intervals);
    }

    public static function compareByStart(TimeInterval $a, TimeInterval $b) {
        if ($a->getStart() == $b->getStart()) return 0;
        if ($a->getStart() <  $b->getStart()) {
            return -1;
        }
        else {
            return 1;
        }
    }

    public static function intervalArraysEqual($array1, $array2) {
        if (count($array1) != count($array2)) {
            return false;
        }

        for ($i = 0; $i < count($array1); $i++) {
            $a = $array1[$i];
            $b = $array2[$i];

            if ($a->getStart() != $b->getStart()) {
                return false;
            }

            if ($a->getEnd() != $b->getEnd()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create a new interval from day, start, and end
     * @param array $triple
     */
    public static function fromTriple($day, $start, $end) {
        $dayBase = $day*1440;
        return new TimeInterval($dayBase+$start, $dayBase+$end);
    }

    /**
     * Filter intervals, removing those that have lower length than specified
     * @param array $intervals
     * @param int $minLength
     * @return array filtered intervals
     */
    public static function filterByMinLength(array $intervals, $minLength) {
        $ret = array();
        foreach ($intervals as $interval) {
            if ($interval->getLength()>=$minLength) {
                $ret[] = $interval;
            }
        }
        return $ret;
    }
    

}