<?php

/**
 * Lesson form base class.
 *
 * @method Lesson getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLessonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'day'            => new sfWidgetFormInputText(),
      'start'          => new sfWidgetFormInputText(),
      'end'            => new sfWidgetFormInputText(),
      'lesson_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LessonType'), 'add_empty' => false)),
      'room_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Room'), 'add_empty' => false)),
      'subject_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Subject'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'day'            => new sfValidatorInteger(),
      'start'          => new sfValidatorInteger(),
      'end'            => new sfValidatorInteger(),
      'lesson_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LessonType'))),
      'room_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Room'))),
      'subject_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Subject'))),
    ));

    $this->widgetSchema->setNameFormat('lesson[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lesson';
  }

}
