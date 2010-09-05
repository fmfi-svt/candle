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
    1.0219   28561736  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0222   28676168  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0264   28684592  14. sfDoctrineFormGenerator->getUniqueColumnNames() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    1.0265   28686968  15. array_shift() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579


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
    1.0219   28561736  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0222   28676168  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0264   28684592  14. sfDoctrineFormGenerator->getUniqueColumnNames() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:35
    1.0267   28687080  15. array_shift() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:579

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Room', 'column' => array('
Warning: implode(): Invalid arguments passed in /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 40

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
    1.0219   28561736  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0222   28676168  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0269   28685632  14. implode() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:40

'))),
        new sfValidatorDoctrineUnique(array('model' => 'Room', 'column' => array('
Warning: implode(): Invalid arguments passed in /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php on line 40

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
    1.0219   28561736  12. sfGenerator->evalTemplate() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php:109
    1.0222   28676168  13. require('/data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php') /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/generator/sfGenerator.class.php:84
    1.0270   28685936  14. implode() /data/Data/Skola/candle/source/candle/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/data/generator/sfDoctrineForm/default/template/sfDoctrineFormGeneratedTemplate.php:40

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
