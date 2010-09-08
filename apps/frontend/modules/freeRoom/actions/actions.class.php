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

        $this->form->bind($request->getParameter($this->form->getName()));

        $this->roomIntervals = null;
        $this->queryIntervals = null;

        if ($this->form->isValid()) {

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
        }

    }

    public function executeParams(sfWebRequest $request) {
        $this->form = new FreeRoomSearchForm();
        $this->roomIntervals = null;
        $this->setTemplate('search');
    }

}