<?php

/**
 * StudentGroupLessons filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStudentGroupLessonsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentGroup'), 'add_empty' => true)),
      'lesson_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'student_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StudentGroup'), 'column' => 'id')),
      'lesson_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Lesson'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('student_group_lessons_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentGroupLessons';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'student_group_id' => 'ForeignKey',
      'lesson_id'        => 'ForeignKey',
    );
  }
}
