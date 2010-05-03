<?php

class ChangePasswordForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'old_password' => new sfWidgetFormInputPassword(),
            'password' => new sfWidgetFormInputPassword(),
            'password_repeat' => new sfWidgetFormInputPassword()
        ));

        $this->setValidators(array(
            'old_password' => new sfValidatorString(),
            'password' => new sfValidatorString(),
            'password_repeat' => new sfValidatorString()
        ));

        $this->widgetSchema->setLabels(array(
            'old_password' => 'Staré heslo',
            'password' => 'Heslo',
            'password_repeat' => 'Heslo (znova)'
        ));

        $this->widgetSchema->setNameFormat('changePassword[%s]');

    }
}
