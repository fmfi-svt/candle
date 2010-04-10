<?php

/**
 * LinkedLessons form base class.
 *
 * @method LinkedLessons getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLinkedLessonsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lesson1_id' => new sfWidgetFormInputHidden(),
      'lesson2_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'lesson1_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'lesson1_id', 'required' => false)),
      'lesson2_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'lesson2_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('linked_lessons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'LinkedLessons';
  }

}
