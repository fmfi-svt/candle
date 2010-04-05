<?php

class EditableTimetableForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText()
        ));
        
        $this->setValidators(array(
            'name' => new sfValidatorString()
        ));
        
        $this->widgetSchema->setLabels(array(
            'name' => 'Názov rozvrhu'
        ));

    }
}
