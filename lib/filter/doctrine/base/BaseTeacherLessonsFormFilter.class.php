<?php

/**
 * TeacherLessons filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTeacherLessonsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'teacher_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Teacher'), 'add_empty' => true)),
      'lesson_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'teacher_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Teacher'), 'column' => 'id')),
      'lesson_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Lesson'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('teacher_lessons_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeacherLessons';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'teacher_id' => 'ForeignKey',
      'lesson_id'  => 'ForeignKey',
    );
  }
}
