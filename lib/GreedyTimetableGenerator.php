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


class GreedyTimetableGenerator {

    /**
     * Timetable to modify
     * @var EditableTimetable
     */
    protected $timetable;

    public function __construct(EditableTimetable $timetable) {
        $this->timetable = $timetable;
    }

    /**
     * Try to select best lessons from the timetable using a greedy
     * algorithm.
     */
    public function generate() {

        $lessons = $this->timetable->getLessons();
        $lessonsBySubjects = $this->groupLessonsBySubjects($lessons);
        $lessonsByGroups = $this->groupLessonsByChoices($lessonsBySubjects);

        
        

    }

    /**
     * Group lessons (already grouped by subject) to groups by type along with
     * count necessary to satisfy subject.
     * @param array(array('lessons'=>array(Lesson), 'subject'=>Subject)) $lessonsBySujects
     * @return array(
     *              array(
     *                  'groups'=> array(
     *                      string => array(
     *                          'lessons'=>array(Lesson),
     *                          'needsHours'=>int
     *                      ),
     *                  ),
     *                  'subject'=>Subject
     *              )
     *          )
     */
    protected function groupLessonsByChoices(array $lessonsBySubjects) {
        $choices = array();
        $rozsahParser = new RozsahParser();

        foreach ($lessonsBySubjects as $subjectGroup) {
            $item = array();

            $subject = $subjectGroup['subject'];

            $subjectHours = $rozsahParser->parse($subject['rozsah']);

            $item['subject'] = $subject;

            $groups = array();

            foreach ($subjectGroup['lessons'] as $lesson) {
                $type = $lesson['LessonType']['code'];
                if (isset($groups[$type])) {
                    $groups[$type]['lessons'][] = $lesson;
                }
                else {
                    // default to one hour per type in case we don't know
                    // how does it look like
                    $needsHours = 1;
                    if (isset($subjectHours[strtoupper($type)])) {
                        $needsHours = $subjectHours[strtoupper($type)];
                    }
                    $groups[$type] = array('lessons'=>array($lesson), 'needsHours'=>$needsHours);
                }
            }

            $item['groups'] = $groups;
            $choices[] = $item;
        }

        return $choices;
    }

    /**
     * Group lessons by its corresponding subject
     * @param array(Lesson) $lessons
     * @return array(array('lessons'=>array(Lesson), 'subject'=>Subject))
     */
    protected function groupLessonsBySubjects(array $lessons) {
        $subjects = array();

        foreach ($lessons as $lesson) {
            
            $subject = $lesson['Subject'];
            $key = $subject['code'];

            if (isset($subjects[$key])) {
                $subjects[$key]['lessons'][] = $lesson;
            }
            else {
                $subjects[$key] = array('lessons'=>array($lesson), 'subject'=>$subject);
            }

        }

        return $subjects;
    }

}

