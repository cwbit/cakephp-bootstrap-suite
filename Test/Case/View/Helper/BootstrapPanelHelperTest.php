<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapPanelHelper', 'Bootstrap.View/Helper');

class BootstrapPanelHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Panel = new BootstrapPanelHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Panel->add('My Glorious Body!');

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel'
					],
			],
			$a->toString(),
			'Failed asserting main Panel attributes'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class' => 'panel-body',
				],
				'content'=>'My Glorious Body!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Body'
			);

		$this->assertNotTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-heading'
					],
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Heading should not exist - it was not specified'
			);

		$this->assertNotTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-footer'
					],
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			], 
			$a->toString(),
			'Footer should not exist - it was not specified'
			);

		
	}
	public function testWithDefaultsTitleAndBody(){
		$a = $this->Panel->add('My Title', 'My Glorious Body!');

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel'
					],
			],
			$a->toString(),
			'Failed asserting main Panel attributes'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class' => 'panel-body',
				],
				'content'=>'My Glorious Body!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Body'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-heading'
					],
				'content'=> 'My Title',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Heading'
			);

		$this->assertNotTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-footer'
					],
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			], 
			$a->toString(),
			'Footer should not exist - it was not specified'
			);

		
	}
	public function testWithDefaultsTitleBodyAndFooter(){
		$a = $this->Panel->add('My Title', 'My Glorious Body!', 'A most Glorious Footer!');

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel'
					],
			],
			$a->toString(),
			'Failed asserting main Panel attributes'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class' => 'panel-body',
				],
				'content'=>'My Glorious Body!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Body'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-heading'
					],
				'content'=> 'My Title',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Heading'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-footer'
					],
				'content' => 'A most Glorious Footer!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			], 
			$a->toString(),
			'Failed asserting Panel Footer'
			);
		
	}

	public function testAdvanced(){
		$a = $this->Panel->add();

		$a->Body->create('My Glorious Body!');
		$a->Header->create()->Title->create('My Title');
		$a->Footer->create('A most Glorious Footer!');

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class' => 'panel-body',
				],
				'content'=>'My Glorious Body!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Body'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-heading'
					],
				'content'=> 'My Title',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			],
			$a->toString(),
			'Failed asserting Panel Heading'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'panel-footer'
					],
				'content' => 'A most Glorious Footer!',
				'parent' => [
					'tag'=>'div', 
					'attributes' => [
						'class' => 'panel'
						],
				]
			], 
			$a->toString(),
			'Failed asserting Panel Footer'
			);
	}

}
?>