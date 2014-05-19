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

	public function testWrap(){
		$this->BootstrapHelperWrappedEntityCollection->add(['id'=>'test'],['tag'=>'span']);
		
		$this->assertInstanceOf('BootstrapHelperEntity', $this->BootstrapHelperWrappedEntityCollection->get('test'));
		
		$this->assertTag(['tag'=>'div','child'=>['tag'=>'span']], $this->BootstrapHelperWrappedEntityCollection->toString());

	}

}?>