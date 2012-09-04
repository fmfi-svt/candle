<?php

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property varchar $login
 * @property Doctrine_Collection $UserTimetable
 * 
 * @method integer             getId()            Returns the current record's "id" value
 * @method varchar             getLogin()         Returns the current record's "login" value
 * @method Doctrine_Collection getUserTimetable() Returns the current record's "UserTimetable" collection
 * @method User                setId()            Sets the current record's "id" value
 * @method User                setLogin()         Sets the current record's "login" value
 * @method User                setUserTimetable() Sets the current record's "UserTimetable" collection
 * 
 * @package    candle
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('login', 'varchar', 50, array(
             'type' => 'varchar',
             'notnull' => true,
             'unique' => true,
             'length' => 50,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('UserTimetable', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}