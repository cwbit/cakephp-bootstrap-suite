<?php


App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapListGroupHelper', 'Bootstrap.View/Helper');

class BootstrapListGroupHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->ListGroup = new BootstrapListGroupHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->ListGroup->add();
		$a->add('Foo');
		$a->add('Bar');
		
		$this->assertTag(
			[
				'tag'=>'ul', 
				'attributes' => [
					'class'=>'list-group'
					],
				'child'=>[
					'tag' => 'li',
					'attributes' => [
						'class' => 'list-group-item'
						]
					],
				'children' => [
					'count' => 2,
					]
				],
			$a->toString(),
			'Failed asserting list group with defaults '//.print_r($a->toString(),true).""
			);
	}

}
?>