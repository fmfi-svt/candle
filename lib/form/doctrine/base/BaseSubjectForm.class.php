<?php

/**
 * Subject form base class.
 *
 * @method Subject getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSubjectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'code'        => new sfWidgetFormInputText(),
      'short_code'  => new sfWidgetFormInputText(),
      'creditValue' => new sfWidgetFormInputText(),
      'rozsah'      => new sfWidgetFormInputText(),
      'external_id' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 100)),
      'code'        => new sfValidatorString(array('max_length' => 30)),
      'short_code'  => new sfValidatorString(array('max_length' => 10)),
      'creditValue' => new sfValidatorInteger(),
      'rozsah'      => new sfValidatorString(array('max_length' => 30)),
      'external_id' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('subject[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Subject';
  }

}
