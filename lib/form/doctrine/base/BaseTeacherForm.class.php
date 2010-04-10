<?php

/**
 * Teacher form base class.
 *
 * @method Teacher getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTeacherForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'given_name'  => new sfWidgetFormInputText(),
      'family_name' => new sfWidgetFormInputText(),
      'iniciala'    => new sfWidgetFormInputText(),
      'oddelenie'   => new sfWidgetFormInputText(),
      'katedra'     => new sfWidgetFormInputText(),
      'external_id' => new sfWidgetFormInputText(),
      'lesson_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lesson')),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'given_name'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'family_name' => new sfValidatorString(array('max_length' => 50)),
      'iniciala'    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'oddelenie'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'katedra'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'external_id' => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'lesson_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('teacher[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Teacher';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['lesson_list']))
    {
      $this->setDefault('lesson_list', $this->object->Lesson->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveLessonList($con);

    parent::doSave($con);
  }

  public function saveLessonList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['lesson_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Lesson->getPrimaryKeys();
    $values = $this->getValue('lesson_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Lesson', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Lesson', array_values($link));
    }
  }

}
