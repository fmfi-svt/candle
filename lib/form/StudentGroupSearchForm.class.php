<?php

class StudentGroupSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'showStudentGroups' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'showStudentGroups' => new sfValidatorString(array('required'=>false))
        ));

        $this->widgetSchema->setLabels(array(
            'showStudentGroups' => 'Hľadať krúžky'
        ));

        $this->disableLocalCSRFProtection();
    }
}
