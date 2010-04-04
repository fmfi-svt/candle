<?php

/**
 * Teacher filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTeacherFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'given_name'  => new sfWidgetFormFilterInput(),
      'family_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'iniciala'    => new sfWidgetFormFilterInput(),
      'oddelenie'   => new sfWidgetFormFilterInput(),
      'katedra'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'given_name'  => new sfValidatorPass(array('required' => false)),
      'family_name' => new sfValidatorPass(array('required' => false)),
      'iniciala'    => new sfValidatorPass(array('required' => false)),
      'oddelenie'   => new sfValidatorPass(array('required' => false)),
      'katedra'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('teacher_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Teacher';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'given_name'  => 'Text',
      'family_name' => 'Text',
      'iniciala'    => 'Text',
      'oddelenie'   => 'Text',
      'katedra'     => 'Text',
    );
  }
}
