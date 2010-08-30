<?php

/**
 * Room form base class.
 *
 * @method Room getObject() Returns the current form's model object
 *
 * @package    candle
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseRoomForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'name'         => new sfWidgetFormInputText(),
      'room_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RoomType'), 'add_empty' => false)),
      'capacity'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 30)),
      'room_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('RoomType'))),
      'capacity'     => new sfValidatorInteger(),
    ));


Warning: array_shift() expects parameter 1 to be array, string given in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php on line 579

Call Stack:
    0.0003     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0033    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1213   11556432   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1258   11559968   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1260   11561864   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1408   12595552   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7761   15337912   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7762   15344368   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7765   15348808   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7849   16575688  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8270   23271144  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9684   28003336  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9688   28118616  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9732   28124696  14. sfDoctrineFormGenerator->getUniqueColumnNames() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    0.9734   28127064  15. array_shift() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579


Warning: array_shift() expects parameter 1 to be array, string given in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php on line 579

Call Stack:
    0.0003     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0033    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1213   11556432   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1258   11559968   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1260   11561864   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1408   12595552   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7761   15337912   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7762   15344368   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7765   15348808   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7849   16575688  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8270   23271144  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9684   28003336  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9688   28118616  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9732   28124696  14. sfDoctrineFormGenerator->getUniqueColumnNames() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    0.9735   28127176  15. array_shift() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Room', 'column' => array('
Warning: implode(): Invalid arguments passed in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 40

Call Stack:
    0.0003     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0033    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1213   11556432   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1258   11559968   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1260   11561864   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1408   12595552   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7761   15337912   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7762   15344368   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7765   15348808   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7849   16575688  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8270   23271144  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9684   28003336  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9688   28118616  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9736   28125736  14. implode() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:40

'))),
        new sfValidatorDoctrineUnique(array('model' => 'Room', 'column' => array('
Warning: implode(): Invalid arguments passed in /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 40

Call Stack:
    0.0003     635408   1. {main}() /home/anty/Skola/candle/candle/symfony:0
    0.0033    1054496   2. include('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php') /home/anty/Skola/candle/candle/symfony:14
    0.1213   11556432   3. sfSymfonyCommandApplication->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/cli.php:20
    0.1258   11559968   4. sfTask->runFromCLI() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/command/sfSymfonyCommandApplication.class.php:76
    0.1260   11561864   5. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:97
    0.1408   12595552   6. sfDoctrineBuildTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7761   15337912   7. sfTask->run() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php:169
    0.7762   15344368   8. sfBaseTask->doRun() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfTask.class.php:173
    0.7765   15348808   9. sfDoctrineBuildFormsTask->execute() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/task/sfBaseTask.class.php:68
    0.7849   16575688  10. sfGeneratorManager->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php:64
    0.8270   23271144  11. sfDoctrineFormGenerator->generate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGeneratorManager.class.php:113
    0.9684   28003336  12. sfGenerator->evalTemplate() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    0.9688   28118616  13. require('/home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    0.9737   28126040  14. implode() /home/anty/Skola/candle/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:40

'))),
      ))
    );

    $this->widgetSchema->setNameFormat('room[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Room';
  }

}
