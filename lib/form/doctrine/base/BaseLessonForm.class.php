<?php

/**
 * Lesson form base class.
 *
 * @method Lesson getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLessonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'day'                => new sfWidgetFormInputText(),
      'start'              => new sfWidgetFormInputText(),
      'end'                => new sfWidgetFormInputText(),
      'lesson_type_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LessonType'), 'add_empty' => false)),
      'room_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Room'), 'add_empty' => false)),
      'subject_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Subject'), 'add_empty' => false)),
      'student_group_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'StudentGroup')),
      'teacher_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Teacher')),
      'linked_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Lesson')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'day'                => new sfValidatorInteger(),
      'start'              => new sfValidatorInteger(),
      'end'                => new sfValidatorInteger(),
      'lesson_type_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LessonType'))),
      'room_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Room'))),
      'subject_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Subject'))),
      'student_group_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StudentGroup', 'required' => false)),
      'teacher_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Teacher', 'required' => false)),
      'linked_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lesson[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lesson';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['student_group_list']))
    {
      $this->setDefault('student_group_list', $this->object->StudentGroup->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['teacher_list']))
    {
      $this->setDefault('teacher_list', $this->object->Teacher->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['linked_list']))
    {
      $this->setDefault('linked_list', $this->object->Linked->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveStudentGroupList($con);
    $this->saveTeacherList($con);
    $this->saveLinkedList($con);

    parent::doSave($con);
  }

  public function saveStudentGroupList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['student_group_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->StudentGroup->getPrimaryKeys();
    $values = $this->getValue('student_group_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('StudentGroup', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('StudentGroup', array_values($link));
    }
  }

  public function saveTeacherList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['teacher_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Teacher->getPrimaryKeys();
    $values = $this->getValue('teacher_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Teacher', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Teacher', array_values($link));
    }
  }

  public function saveLinkedList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['linked_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Linked->getPrimaryKeys();
    $values = $this->getValue('linked_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Linked', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Linked', array_values($link));
    }
  }

}
