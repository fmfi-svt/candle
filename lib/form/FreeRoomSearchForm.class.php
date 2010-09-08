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

class FreeRoomSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'requiredAmount' => new sfWidgetFormInputText(),
            'searchIntervals' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'requiredAmount' => new TimeAmountValidator(array('required'=>true)),
            'searchIntervals' => new DayTimeSpecValidator(array('required'=>true))
        ));

        $this->widgetSchema->setLabels(array(
            'requiredAmount' => 'Požadovaná dĺžka okna',
            'searchIntervals' => 'Hľadať v časových intervaloch'
        ));

        $this->disableLocalCSRFProtection();
        $this->widgetSchema->setNameFormat('freeRoomSearch[%s]');
    }
}