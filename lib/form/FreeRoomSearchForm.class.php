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
            'searchIntervals' => new sfWidgetFormInputText(),
            'minimalRoomCapacity' => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'requiredAmount' => new TimeAmountValidator(array('required'=>true)),
            'searchIntervals' => new DayTimeSpecValidator(array('required'=>true)),
            'minimalRoomCapacity' => new sfValidatorInteger(array('required'=>false, 'empty_value' => 1)),
        ));

        $this->widgetSchema->setLabels(array(
            'requiredAmount' => 'Požadovaná dĺžka okna',
            'searchIntervals' => 'Hľadať v časových intervaloch',
            'minimalRoomCapacity' => 'Minimálna kapacita miestnosti',
        ));

        $this->widgetSchema->setHelps(array(
            'requiredAmount' => 'Aká musí byť minimálna dĺžka voľného okna v miestnosti 
                (t.j. napríklad dĺžka hodiny, ktorú by ste chceli presunúť).
                Hodnoty sa dajú zadať v tvare <span class="example">1:35</span>,
                <span class="example">1h 35m</span>,
                <span class="example">1h</span>,
                <span class="example">35m</span> alebo
                <span class="example">2 v.h</span>',
            'searchIntervals' => 'Časové intervaly, na ktoré obmedziť vyhľadávanie. 
                Tu sa zadáva čiarkami oddelený zoznam intervalov,
                v ktorých hľadať (hľadá sa v ich zjednotení).
                Jednotlivé intervaly sú zadávané vo formáte
                <span class="example">Po 16:30-18:40</span> alebo
                <span class="example">Po 16:30-Ut 7:00</span>
                (dni môžu byť prípadne aj rozpísané ako v
                <span class="example">Pondelok 16:30-Utorok 12:00</span>).
                Pri zadávaní viacerých intervalov je možné vynechať názov dňa
                v prípade, že už bol špecifikovaný, napr.
                <span class="example">Po 16:30-18:00, 19:00-20:00</span>.
                Túto možnosť nie je možné využiť, ak je špecifikovaný aj
                druhý deň intervalu, pretože by vstup nebol jednoznačný (t.j.
                nasledovné vyhodí chybu:
                <span class="bad example">Po 16:30-Ut 18:00, 19:00-20:00</span>)',
        ));

        $this->disableLocalCSRFProtection();
        $this->widgetSchema->setNameFormat('freeRoomSearch[%s]');
    }
}
