<?php

/**
 * Room filter form base class.
 *
 * @package    candle
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseRoomFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'room_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RoomType'), 'add_empty' => true)),
      'capacity'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'         => new sfValidatorPass(array('required' => false)),
      'room_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('RoomType'), 'column' => 'id')),
      'capacity'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('room_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Room';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'name'         => 'Text',
      'room_type_id' => 'ForeignKey',
      'capacity'     => 'Number',
    );
  }
}
