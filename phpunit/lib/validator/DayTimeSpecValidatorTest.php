<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

class DayTimeSpecValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var DayTimeSpecValidator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new DayTimeSpecValidator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    public function testClean() {
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('Po 00:00-00:10'), array(new TimeInterval(0,10))));
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('Po 00:00-Po 00:10'), array(new TimeInterval(0,10))));
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('Po 1:10-2:20'), array(new TimeInterval(70,140))));
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('Ut 00:00-00:00'), array(new TimeInterval(24*60,24*60))), 'Funguje jeden den');
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('Ut 00:00-St 00:00'), array(new TimeInterval(24*60,2*24*60))), 'Funguju dva dni');
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('  Ut 00:00-St 00:00, Po 1:00-2:00, Ut 00:00-2:00'), array(new TimeInterval(24*60,2*24*60), new TimeInterval(60,120), new TimeInterval(24*60,26*60))), 'Funguje komplexne zadanie');
        $this->assertTrue(TimeInterval::intervalArraysEqual($this->object->clean('  Ut 00:00-10:00, 11:00-20:00, Ut 00:00-2:00'), array(new TimeInterval(24*60,34*60), new TimeInterval(35*60,44*60), new TimeInterval(24*60,26*60))), 'Funguje komplexne zadanie');
    }

    public function testCleanNegativeIntervals() {
        $this->setExpectedException('sfValidatorError');
        $this->object->clean('St 10:00-00:10');
    }

    public function testCleanAmbiguousDay() {
        $this->setExpectedException('sfValidatorError');
        $this->object->clean('St 10:00-Stv 00:10,12:00-13:00');
    }

}
?>
