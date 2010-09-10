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

class RegistrationForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'username' => new sfWidgetFormInputText(),
            'password' => new sfWidgetFormInputPassword(),
            'password_repeat' => new sfWidgetFormInputPassword()
        ));

        $this->setValidators(array(
            'username' => new sfValidatorString(),
            'password' => new sfValidatorString(array('min_length'=>6)),
            'password_repeat' => new sfValidatorString(array('min_length'=>6)),
        ));

        //$this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password_repeat', sfValidatorSchemaCompare::EQUAL, 'password'));

        $this->widgetSchema->setLabels(array(
            'username' => 'Prihlasovacie meno',
            'password' => 'Heslo',
            'password_repeat' => 'Heslo (znova)'
        ));

        $this->widgetSchema->setNameFormat('registration[%s]');

    }
}
