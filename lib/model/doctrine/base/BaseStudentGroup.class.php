<?php

/**
 * BaseStudentGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $Lessons
 * @property Doctrine_Collection $Lesson
 * @property Doctrine_Collection $StudentGroupLessons
 * 
 * @method string              getName()                Returns the current record's "name" value
 * @method Doctrine_Collection getLessons()             Returns the current record's "Lessons" collection
 * @method Doctrine_Collection getLesson()              Returns the current record's "Lesson" collection
 * @method Doctrine_Collection getStudentGroupLessons() Returns the current record's "StudentGroupLessons" collection
 * @method StudentGroup        setName()                Sets the current record's "name" value
 * @method StudentGroup        setLessons()             Sets the current record's "Lessons" collection
 * @method StudentGroup        setLesson()              Sets the current record's "Lesson" collection
 * @method StudentGroup        setStudentGroupLessons() Sets the current record's "StudentGroupLessons" collection
 * 
 * @package    candle
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseStudentGroup extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('student_group');
        $this->hasColumn('name', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '30',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Lesson as Lessons', array(
             'refClass' => 'StudentGroupLessons',
             'local' => 'student_group_id',
             'foreign' => 'lesson_id'));

        $this->hasMany('Lesson', array(
             'refClass' => 'StudentGroupLessons',
             'local' => 'group_id',
             'foreign' => 'lesson_id'));

        $this->hasMany('StudentGroupLessons', array(
             'local' => 'id',
             'foreign' => 'student_group_id'));
    }
}