<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('WellHelper', 'Bootstrap.View/Helper');

class WellHelperTest extends CakeTestCase {
    public function setUp() {
	    parent::setUp();
	    $Controller = new Controller();
	    $View = new View($Controller);
	    $this->Well = new WellHelper($View);
	}

    public function testWellSimple() {
    	$result = $this->Well->create('BLAH');
    	$this->assertInternalType('string', $result);
    	$this->assertContains('BLAH', $result);
    	$this->assertStringMatchesFormat("%sclass='%swell%s", $result); #http://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.assertions.assertStringMatchesFormat
    
    	$result = $this->Well->create('This will be overriden by the explicit content call',['content'=>'foo']);
    	$this->assertContains('foo', $result);
    	$this->assertNotContains('This will be overriden by the explicit content call', $result);

    	$result = $this->Well->create('BLAH',['tag'=>'span','size'=>'lg']);
    	$this->assertStringMatchesFormat("<%Sspan%s/span%S>", $result, 'Check Tag Override'); #http://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.assertions.assertStringMatchesFormat
    	$this->assertStringMatchesFormat("%sclass='%swell-lg%s", $result, 'Check Option-into-Option Replacement');

    	$result = $this->Well->create(['id'=>'2','name'=>'Foo'],['content'=>':name (:id)']);
    	$this->assertContains("Foo (2)", $result, 'Check data replacement'); #http://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.assertions.assertStringMatchesFormat

    }

}
?>