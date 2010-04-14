<?php

/**
 * UserTimetableLessons form base class.
 *
 * @method UserTimetableLessons getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserTimetableLessonsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'user_timetable_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserTimetable'), 'add_empty' => false)),
      'lesson_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'), 'add_empty' => false)),
      'highlighted'       => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_timetable_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserTimetable'))),
      'lesson_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Lesson'))),
      'highlighted'       => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('user_timetable_lessons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserTimetableLessons';
  }

}
