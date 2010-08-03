<?php

class EditableTimetablePublishForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'slug' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'slug' => new sfValidatorRegex(array('pattern'=>'/^[a-z0-9-]+$/'), array('invalid'=>'Povolené sú len malé písmená bez diakritiky, číslice a pomlčka'))
        ));

        $this->widgetSchema->setLabels(array(
            'slug' => 'Zverejniť na /rozvrh/'
        ));

    }
}
