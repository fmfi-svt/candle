<?php

class LessonSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'showLessons' => new sfWidgetFormInputText()
        ));
        
        $this->setValidators(array(
            'showLessons' => new sfValidatorString(array('required'=>false))
        ));
        
        $this->widgetSchema->setLabels(array(
            'showLessons' => 'Hľadať hodiny'
        ));
        
        $this->disableLocalCSRFProtection();
    }
}
