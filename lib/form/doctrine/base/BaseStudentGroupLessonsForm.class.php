<?php

/**
 * StudentGroupLessons form base class.
 *
 * @method StudentGroupLessons getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStudentGroupLessonsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_group_id' => new sfWidgetFormInputHidden(),
      'lesson_id'        => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'student_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'student_group_id', 'required' => false)),
      'lesson_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'lesson_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('student_group_lessons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentGroupLessons';
  }

}
