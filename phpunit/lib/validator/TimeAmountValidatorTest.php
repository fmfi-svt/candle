<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

class TimeAmountValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var TimeAmountValidator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new TimeAmountValidator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    public function testClean() {
        $this->assertEquals(120, $this->object->clean('2h'));
        $this->assertEquals(125, $this->object->clean('2h5m'));
        $this->assertEquals(125, $this->object->clean('2h 5m'));
        $this->assertEquals(125, $this->object->clean('2:05'));
        $this->assertEquals(5, $this->object->clean('5m'));
        $this->assertEquals(150, $this->object->clean('2.5h'));
        $this->assertEquals(150, $this->object->clean('2,5h'));
        $this->assertEquals(90, $this->object->clean('2vh'));
        $this->assertEquals(90, $this->object->clean('2 v.h'));
        $this->assertEquals(90, $this->object->clean('2 v.h.'));
    }

    public function testCleanWrongInput() {
        $this->setExpectedException('sfValidatorError');
        $this->object->clean('jksdjdks');
    }

    public function testCleanWrongInput2() {
        $this->setExpectedException('sfValidatorError');
        $this->object->clean('h5m');
    }


    public function testCleanWrongInput3() {
        $this->setExpectedException('sfValidatorError');
        $this->object->clean('5hm');
    }

}
?>
