<?php

/**
 * Lesson filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLessonFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'day'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lesson_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LessonType'), 'add_empty' => true)),
      'room_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Room'), 'add_empty' => true)),
      'subject_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Subject'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'day'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lesson_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('LessonType'), 'column' => 'id')),
      'room_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Room'), 'column' => 'id')),
      'subject_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Subject'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('lesson_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lesson';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'day'            => 'Number',
      'start'          => 'Number',
      'end'            => 'Number',
      'lesson_type_id' => 'ForeignKey',
      'room_id'        => 'ForeignKey',
      'subject_id'     => 'ForeignKey',
    );
  }
}
