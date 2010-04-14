<?php

/**
 * UserTimetableLessons filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserTimetableLessonsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_timetable_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserTimetable'), 'add_empty' => true)),
      'lesson_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'), 'add_empty' => true)),
      'highlighted'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'user_timetable_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserTimetable'), 'column' => 'id')),
      'lesson_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Lesson'), 'column' => 'id')),
      'highlighted'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('user_timetable_lessons_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserTimetableLessons';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'user_timetable_id' => 'ForeignKey',
      'lesson_id'         => 'ForeignKey',
      'highlighted'       => 'Boolean',
    );
  }
}
