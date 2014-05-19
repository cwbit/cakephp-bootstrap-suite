<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BHWECT extends BootstrapHelperWrappedEntityCollection{
	public $_options = ['tag'=>'div'];
}

class BootstrapHelperWrappedEntityCollectionTest extends CakeTestCase{

	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);

		$this->BootstrapHelperWrappedEntityCollection = new BHWECT($View);
	}

	public function tearDown(){
		parent::tearDown();
		unset($this->BootstrapHelperWrappedEntityCollection);
	}

	public function testWrap(){
		
		$this->BootstrapHelperWrappedEntityCollection->add(['id'=>'test'],['tag'=>'span']);
		
		$this->assertInstanceOf('BootstrapHelperEntity', $this->BootstrapHelperWrappedEntityCollection->get('test'), 'Entity Collection entity not a (sub)class of BootstrapHelperEntity');
		
		$this->assertTag(['tag'=>'div','child'=>['tag'=>'span']], $this->BootstrapHelperWrappedEntityCollection->toString(), 'Could not assert Entity Collection wrap. <pre>'.print_r($this->BootstrapHelperWrappedEntityCollection->toString()).'</pre>');

	}
	
	public function testEmpty(){
		$this->assertEquals('', $this->BootstrapHelperWrappedEntityCollection->toString(), 'Entity Colections should return a blank string when they have not been create()d. This is to allow more advanced collections to automatically \'ignore\' un-created peices of themselves'); 

		$this->BootstrapHelperWrappedEntityCollection->add('test');
		$this->assertNotEquals('', $this->BootstrapHelperWrappedEntityCollection->toString(), 'Entity Collection still considering itself un-created (EMPTY) after EntityCollection::add()'); 
	}
}?>