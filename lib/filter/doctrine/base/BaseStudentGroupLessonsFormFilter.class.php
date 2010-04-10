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
    ));

    $this->setValidators(array(
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
      'student_group_id' => 'Number',
      'lesson_id'        => 'Number',
    );
  }
}
