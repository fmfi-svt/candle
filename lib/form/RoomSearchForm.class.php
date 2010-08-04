<?php

class RoomSearchForm extends sfForm {
    public function configure() {
        $this->setWidgets(array(
            'showRooms' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'showRooms' => new sfValidatorString(array('required'=>false))
        ));

        $this->widgetSchema->setLabels(array(
            'showRooms' => 'Hľadať miestnosti'
        ));

        $this->disableLocalCSRFProtection();
    }
}
