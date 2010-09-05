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
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 30)),
      'lessons_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Lesson', 'required' => false)),
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
    1.0113   28554520  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0116   28668976  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0143   28675080  14. sfDoctrineFormGenerator->getUniqueColumnNames() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    1.0145   28677280  15. array_shift() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'StudentGroup', 'column' => array('
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
    1.0113   28554520  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0116   28668976  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0147   28675872  14. implode() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:44

')))
    );

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

  }

  protected function doSave($con = null)
  {
    $this->saveLessonsList($con);

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

}
