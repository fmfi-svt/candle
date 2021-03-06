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
      'external_id'        => new sfWidgetFormInputText(),
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
      'external_id'        => new sfValidatorInteger(array('required' => false)),
      'student_group_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StudentGroup', 'required' => false)),
      'teacher_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Teacher', 'required' => false)),
      'linked_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
    ));


Warning: array_shift() expects parameter 1 to be array, string given in /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php on line 579

Call Stack:
    0.0002     634272   1. {main}() /data/Data/Skola/candle/source/candle/symfony:0
    0.0031    1053392   2. include('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/cli.php') /data/Data/Skola/candle/source/candle/symfony:14
    0.0976   11651272   3. sfSymfonyCommandApplication->run() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1018   11654808   4. sfTask->runFromCLI() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1020   11656704   5. sfBaseTask->doRun() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1123   12690896   6. sfDoctrineBuildTask->execute() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7534   15443112   7. sfTask->run() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7536   15449568   8. sfBaseTask->doRun() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7539   15453952   9. sfDoctrineBuildFormsTask->execute() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7605   16682400  10. sfGeneratorManager->generate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8156   23378944  11. sfDoctrineFormGenerator->generate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9724   28409008  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9729   28524712  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9953   28652760  14. sfDoctrineFormGenerator->getUniqueColumnNames() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    0.9956   28654984  15. array_shift() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Lesson', 'column' => array('
Warning: implode(): Invalid arguments passed in /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 44

Call Stack:
    0.0002     634272   1. {main}() /data/Data/Skola/candle/source/candle/symfony:0
    0.0031    1053392   2. include('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/cli.php') /data/Data/Skola/candle/source/candle/symfony:14
    0.0976   11651272   3. sfSymfonyCommandApplication->run() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1018   11654808   4. sfTask->runFromCLI() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1020   11656704   5. sfBaseTask->doRun() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1123   12690896   6. sfDoctrineBuildTask->execute() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7534   15443112   7. sfTask->run() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7536   15449568   8. sfBaseTask->doRun() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7539   15453952   9. sfDoctrineBuildFormsTask->execute() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7605   16682400  10. sfGeneratorManager->generate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8156   23378944  11. sfDoctrineFormGenerator->generate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9724   28409008  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9729   28524712  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9959   28653544  14. implode() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:44

')))
    );

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
