<?php

class TeacherSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'showTeachers' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'showTeachers' => new sfValidatorString(array('required'=>false))
        ));

        $this->widgetSchema->setLabels(array(
            'showTeachers' => 'Hľadať učiteľov'
        ));

        $this->disableLocalCSRFProtection();
    }
}
