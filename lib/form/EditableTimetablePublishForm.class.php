<?php

class EditableTimetablePublishForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'slug' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'slug' => new sfValidatorString()
        ));

        $this->widgetSchema->setLabels(array(
            'slug' => 'Publikovať ako'
        ));

    }
}
