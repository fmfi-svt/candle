<?php

/**
 * FreeRoomInterval filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFreeRoomIntervalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'day'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'room_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Room'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'day'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'room_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Room'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('free_room_interval_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FreeRoomInterval';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'day'     => 'Number',
      'start'   => 'Number',
      'end'     => 'Number',
      'room_id' => 'ForeignKey',
    );
  }
}
