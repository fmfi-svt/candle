<?php

/**
 * Subject form base class.
 *
 * @method Subject getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSubjectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'name'         => new sfWidgetFormInputText(),
      'code'         => new sfWidgetFormInputText(),
      'short_code'   => new sfWidgetFormInputText(),
      'credit_value' => new sfWidgetFormInputText(),
      'rozsah'       => new sfWidgetFormInputText(),
      'external_id'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 100)),
      'code'         => new sfValidatorString(array('max_length' => 50)),
      'short_code'   => new sfValidatorString(array('max_length' => 20)),
      'credit_value' => new sfValidatorInteger(),
      'rozsah'       => new sfValidatorString(array('max_length' => 30)),
      'external_id'  => new sfValidatorString(array('max_length' => 30, 'required' => false)),
    ));


Warning: array_shift() expects parameter 1 to be array, string given in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php on line 579

Call Stack:
    0.0002     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0031    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1203   11556040   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1248   11559576   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1250   11561472   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1398   12595032   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7205   15336816   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7207   15343344   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7209   15347824   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7453   16574352  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8104   23269648  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9385   27996544  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9389   28111720  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9453   28115656  14. sfDoctrineFormGenerator->getUniqueColumnNames() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    0.9455   28117944  15. array_shift() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Subject', 'column' => array('
Warning: implode(): Invalid arguments passed in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 44

Call Stack:
    0.0002     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0031    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1203   11556040   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1248   11559576   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1250   11561472   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1398   12595032   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7205   15336816   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7207   15343344   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7209   15347824   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7453   16574352  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8104   23269648  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9385   27996544  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9389   28111720  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9456   28116440  14. implode() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:44

')))
    );

    $this->widgetSchema->setNameFormat('subject[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Subject';
  }

}
