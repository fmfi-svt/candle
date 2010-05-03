<?php

class RegistrationForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'username' => new sfWidgetFormInputText(),
            'password' => new sfWidgetFormInputPassword(),
            'password_repeat' => new sfWidgetFormInputPassword()
        ));

        $this->setValidators(array(
            'username' => new sfValidatorString(),
            'password' => new sfValidatorString(),
            'password_repeat' => new sfValidatorString()
        ));

        $this->widgetSchema->setLabels(array(
            'username' => 'Prihlasovacie meno',
            'password' => 'Heslo',
            'password_repeat' => 'Heslo (znova)'
        ));

        $this->widgetSchema->setNameFormat('registration[%s]');

    }
}
