<?php

/**
 * Teacher form base class.
 *
 * @method Teacher getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTeacherForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'given_name'  => new sfWidgetFormInputText(),
      'family_name' => new sfWidgetFormInputText(),
      'iniciala'    => new sfWidgetFormInputText(),
      'oddelenie'   => new sfWidgetFormInputText(),
      'katedra'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'given_name'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'family_name' => new sfValidatorString(array('max_length' => 50)),
      'iniciala'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'oddelenie'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'katedra'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('teacher[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Teacher';
  }

}
