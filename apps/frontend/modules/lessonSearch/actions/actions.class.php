<?php

/**

    Copyright 2010, 2011 Martin Sucha

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


class lessonSearchActions extends sfActions {

    public function executeSearch(sfWebRequest $request) {

        $this->form = new LessonIntervalSearchForm();

        $formParamNames = array('searchIntervals');

        $formParamValues = array();

        foreach ($formParamNames as $param) {
            $formParamValues[$param] = $request->getParameterHolder()->get($param);
        }

        $this->form->bind($formParamValues);

        $this->lessonIntervals = null;
        $this->queryIntervals = null;

        if ($this->form->isValid()) {

            $thisUrl = array(
                'sf_route'=>'lessonSearch_search',
                'searchIntervals'=>$formParamValues['searchIntervals']);

            $this->thisUrl = $thisUrl;

            $rawQueryIntervals = $this->form->getValue('searchIntervals');
            $mergedIntervals = TimeInterval::mergeIntervals($rawQueryIntervals);
            $queryIntervalsTriples = TimeInterval::convertIntervalsToTriplesArray($mergedIntervals);
            
            $this->queryIntervals = $mergedIntervals;
            $this->lessonIntervals = Doctrine::getTable('Lesson')
                    ->findIntervals($queryIntervalsTriples);

            Candle::setResponseFormat($request->getRequestFormat(), $this);
        }
        else {
            $thisUrl = array('sf_route'=>'lessonSearch_search');
            $this->thisUrl = $thisUrl;
        }

    }

    public function executeParams(sfWebRequest $request) {
        $this->form = new LessonIntervalSearchForm();
        $this->lessonIntervals = null;
        $this->setTemplate('search');
        $thisUrl = array('sf_route'=>'lessonSearch_search');

        $this->thisUrl = $thisUrl;
    }
    
    public function executeCurrent(sfWebRequest $request) {
        $this->queryTime = time() + 15 * 60;
        $timeInfo = getdate($this->queryTime);
        /*
         * wday = 0 means sunday,
         * we want day = 0 to mean monday
         */
        $day = ($timeInfo['wday'] + 6) % 7;
        $time = $timeInfo['hours'] * 60 + $timeInfo['minutes'];
        $sem_start = sfConfig::get('app_semester_start');
        $sem_end = sfConfig::get('app_semester_end');
        if ($this->queryTime >= $sem_start && $this->queryTime <= $sem_end+86400) {
            $lessons = Doctrine::getTable('Lesson')
                    ->findIntervals(array(array($day, $time, $time)),
                            /* broadMatch = */ true,
                            /* orderBySubjectName = */ true);
        }
        else {
            $lessons = array();
        }
        $lastLesson = null;
        $newLessons = array();
        foreach ($lessons as $lesson) {
            if ($lastLesson !== null) {
                if ($lesson['Room']['name'] !== $lastLesson['Room']['name'] ||
                    $lesson['Subject']['name'] !== $lastLesson['Subject']['name'] ||
                    $lesson['start'] !== $lastLesson['start'] ||
                    $lesson['end'] !== $lastLesson['end']) {
                    $newLessons[] = $lesson;
                }
            }
            else {
                $newLessons[] = $lesson;
            }
            $lastLesson = $lesson;
        }
        $this->lessonIntervals = $newLessons;
        $this->setLayout('layout_kiosk');
    }

}