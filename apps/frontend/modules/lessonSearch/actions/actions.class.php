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

    }

    public function executeParams(sfWebRequest $request) {
        $this->form = new LessonIntervalSearchForm();
        $this->lessonIntervals = null;
        $this->setTemplate('search');
    }

}