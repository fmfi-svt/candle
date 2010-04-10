<?php

/**
 * StudentGroup form base class.
 *
 * @method StudentGroup getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStudentGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'name'         => new sfWidgetFormInputText(),
      'lessons_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lesson')),
      'lesson_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lesson')),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 30)),
      'lessons_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
      'lesson_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('student_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentGroup';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['lessons_list']))
    {
      $this->setDefault('lessons_list', $this->object->Lessons->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['lesson_list']))
    {
      $this->setDefault('lesson_list', $this->object->Lesson->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveLessonsList($con);
    $this->saveLessonList($con);

    parent::doSave($con);
  }

  public function saveLessonsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['lessons_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Lessons->getPrimaryKeys();
    $values = $this->getValue('lessons_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Lessons', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Lessons', array_values($link));
    }
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
