<?php

/**
 * BaseFreeRoomInterval
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $day
 * @property integer $start
 * @property integer $end
 * @property integer $room_id
 * @property Room $Room
 * 
 * @method integer          getDay()     Returns the current record's "day" value
 * @method integer          getStart()   Returns the current record's "start" value
 * @method integer          getEnd()     Returns the current record's "end" value
 * @method integer          getRoomId()  Returns the current record's "room_id" value
 * @method Room             getRoom()    Returns the current record's "Room" value
 * @method FreeRoomInterval setDay()     Sets the current record's "day" value
 * @method FreeRoomInterval setStart()   Sets the current record's "start" value
 * @method FreeRoomInterval setEnd()     Sets the current record's "end" value
 * @method FreeRoomInterval setRoomId()  Sets the current record's "room_id" value
 * @method FreeRoomInterval setRoom()    Sets the current record's "Room" value
 * 
 * @package    candle
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseFreeRoomInterval extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('free_room_interval');
        $this->hasColumn('day', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('start', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('end', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('room_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Room', array(
             'local' => 'room_id',
             'foreign' => 'id'));
    }
}