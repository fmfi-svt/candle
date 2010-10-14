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

class LessonIntervalSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'searchIntervals' => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'searchIntervals' => new DayTimeSpecValidator(array('required'=>true)),
        ));

        $this->widgetSchema->setLabels(array(
            'searchIntervals' => 'Hľadať v časových intervaloch',
        ));

        $this->widgetSchema->setHelps(array(
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
    }
}
