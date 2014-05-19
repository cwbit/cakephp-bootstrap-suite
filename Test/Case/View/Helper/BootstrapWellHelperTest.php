<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapWellHelper', 'Bootstrap.View/Helper');

class BootstrapWellHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Well = new BootstrapWellHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Well->add('My Glorious Body!');
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'well'
					],
				'content'=>'My Glorious Body!'
				],
			$a->toString(),
			'Failed asserting Well class defaults'
			);
	}

}
?>