<?php


App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapAlertHelper', 'Bootstrap.View/Helper');

class BootstrapAlertHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Alert = new BootstrapAlertHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Alert->add('Alert','Oh No!');
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'alert alert-info'
					],
				'child'=>['tag' => 'button']
				],
			$a->toString()
			);
	}

	public function testDefaultDismissable(){
		$a = $this->Alert->add('Alert','Oh No!');
		
		$this->assertTag(
			[
				'tag'=>'div',
				'attributes' => [
					'class'=>'alert-dismissable'
					],
				'child' => [
					'tag'=>'button',
					'attributes' => [
						'class'=>'close',
						'data-dismiss'=>'alert'
						],
					// 'content'=>'&times;',
					],
				],
			$a->toString()
			);

	}

	public function testWithOverrides(){
		$a = $this->Alert->add('test',['context'=>'warning']);

		$this->assertTag(
			[
			'tag'=>'div', 
			'attributes'=>[
				'class' => 'alert-warning',
				],
			'content'=>'test'
			],
			$a->toString());

	}
}
?>