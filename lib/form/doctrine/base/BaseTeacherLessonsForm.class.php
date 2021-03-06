<?php

/**
 * TeacherLessons form base class.
 *
 * @method TeacherLessons getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTeacherLessonsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'teacher_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Teacher'), 'add_empty' => false)),
      'lesson_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'teacher_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Teacher'))),
      'lesson_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'))),
    ));

    $this->widgetSchema->setNameFormat('teacher_lessons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeacherLessons';
  }

}
