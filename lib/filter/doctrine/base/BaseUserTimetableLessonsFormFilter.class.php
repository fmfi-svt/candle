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
      'user_timetable_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lesson_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'selected'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'user_timetable_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lesson_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'selected'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
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
      'user_timetable_id' => 'Number',
      'lesson_id'         => 'Number',
      'selected'          => 'Boolean',
    );
  }
}
