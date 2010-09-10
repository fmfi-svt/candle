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


class freeRoomActions extends sfActions {

    public function executeSearch(sfWebRequest $request) {

        $this->form = new FreeRoomSearchForm();

        $formParamNames = array('requiredAmount', 'searchIntervals', 'minimalRoomCapacity');

        $formParamValues = array();

        foreach ($formParamNames as $param) {
            $formParamValues[$param] = $request->getParameterHolder()->get($param);
        }

        $this->form->bind($formParamValues);

        $this->roomIntervals = null;
        $this->queryIntervals = null;

        if ($this->form->isValid()) {

            $thisUrl = array(
                'sf_route'=>'freeRoom_search',
                'requiredAmount'=>$formParamValues['requiredAmount'],
                'searchIntervals'=>$formParamValues['searchIntervals'],
                'minimalRoomCapacity'=>$formParamValues['minimalRoomCapacity']);

            $this->thisUrl = $thisUrl;

            $minLength = $this->form->getValue('requiredAmount');
            $rawQueryIntervals = $this->form->getValue('searchIntervals');
            $mergedIntervals = TimeInterval::mergeIntervals($rawQueryIntervals);
            $mergedIntervals = TimeInterval::filterByMinLength($mergedIntervals, $minLength);
            $queryIntervalsTriples = TimeInterval::convertIntervalsToTriplesArray($mergedIntervals);
            $minRoomCapacity = $this->form->getValue('minimalRoomCapacity');

            $this->minLength = $minLength;
            $this->queryIntervals = $mergedIntervals;
            $this->roomIntervals = Doctrine::getTable('FreeRoomInterval')
                    ->findIntervals($minLength, $queryIntervalsTriples, $minRoomCapacity);

            foreach ($this->roomIntervals as &$roomInterval) {
                $timeInterval = TimeInterval::fromTriple($roomInterval['day'],
                        $roomInterval['start'], $roomInterval['end']);
                $printableIntervals = $timeInterval->intersectArray($mergedIntervals);
                $printableIntervals = TimeInterval::filterByMinLength($printableIntervals, $minLength);
                $roomInterval['printableIntervals'] = $printableIntervals;
            }

            Candle::setResponseFormat($request->getRequestFormat(), $this);
        }

    }

    public function executeParams(sfWebRequest $request) {
        $this->form = new FreeRoomSearchForm();
        $this->roomIntervals = null;
        $this->setTemplate('search');
    }

}