<?php

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

}
