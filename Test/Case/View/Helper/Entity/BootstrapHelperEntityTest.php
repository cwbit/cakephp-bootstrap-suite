<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');

class BootstrapHelperEntityTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->BootstrapHelperEntity = new BootstrapHelperEntity($View);
	}

	public function testInheritance(){
		$this->assertInstanceOf('BootstrapHelperEntity', $this->BootstrapHelperEntity);
	}

	public function testCreate(){
		$this->BootstrapHelperEntity->_pattern = "<:tag>:content</:tag>";
		$this->BootstrapHelperEntity->_options = ['tag'=>'div'];
		$this->BootstrapHelperEntity->create('test');
		$this->assertInternalType('string', $this->BootstrapHelperEntity->toString());
		$this->assertTag(['tag'=>'div','content'=>'test'], $this->BootstrapHelperEntity->toString());

	}

	public function testCreateWithOptions(){
		$this->BootstrapHelperEntity->_pattern = "<:tag>:content</:tag>";
		$this->BootstrapHelperEntity->_options = ['tag'=>'div'];
		$this->BootstrapHelperEntity->create('test',['tag'=>'span']);
		$this->assertTag(['tag'=>'span','content'=>'test',],$this->BootstrapHelperEntity->toString());
	}

	public function testCreateWithAttributes(){
		// $this->BootstrapHelperEntity->_pattern = "<:tag :htmlAttributes>:content</:tag>";
		$this->BootstrapHelperEntity->_options = ['tag'=>'div'];
		$this->BootstrapHelperEntity->create('test',['class'=>'dummy']);
		$this->assertTag(['tag'=>'div','content'=>'test','attributes'=>['class'=>'dummy']], $this->BootstrapHelperEntity->toString());
	}

	public function testOptionInsertion(){
		#test standard insertion
		$this->BootstrapHelperEntity->_options = ['tag'=>'div'];
		$this->BootstrapHelperEntity->create(['id'=>'1234'],['content'=>':id']);
		$this->assertTag(['tag'=>'div','content'=>'1234'], $this->BootstrapHelperEntity->toString());

		#test option<-option insertion
		$this->BootstrapHelperEntity->_options = ['tag'=>':tag'];
		$this->BootstrapHelperEntity->create('1234',['tag'=>'span']);
		$this->assertTag(['tag'=>'span','content'=>'1234'], $this->BootstrapHelperEntity->toString());		

		#test option<-data insertion
		$this->BootstrapHelperEntity->_options = ['tag'=>'div','content'=>':name'];
		$this->BootstrapHelperEntity->create(['id'=>'1234','name'=>'corn']);
		$this->assertTag(['tag'=>'div','content'=>'corn'], $this->BootstrapHelperEntity->toString());		
		
	}

	public function testKeyRemaps(){
		#test standard insertion
		$this->BootstrapHelperEntity->_options = ['tag'=>'div', 'id'=>':dummy'];
		$this->BootstrapHelperEntity->create(['id'=>'1234'],['content'=>':modelID'],['id'=>'modelID']);
		$this->assertTag(['tag'=>'div','content'=>'1234'], $this->BootstrapHelperEntity->toString());		

	}
	
	public function testValueRemaps(){
		#test standard insertion
		$this->BootstrapHelperEntity->_options = ['tag'=>'div', 'id'=>':dummy'];
		$this->BootstrapHelperEntity->create(['id'=>'1234'],['content'=>':content'], [], ['content'=>'test-:id']);
		$this->assertTag(['tag'=>'div','content'=>'test-1234'], $this->BootstrapHelperEntity->toString());		

	}

	public function testId(){
		$this->BootstrapHelperEntity->create(['id'=>'test123','content'=>'foo']);
		$this->assertEquals('test123', $this->BootstrapHelperEntity->id);
		$this->assertNotTag(['attributes'=>['id'=>'test1234']], $this->BootstrapHelperEntity->toString(),"Attribute::id should NOT be set by default.");
	}

	public function testIdAttribute(){
		$this->BootstrapHelperEntity->_options = ['tag'=>'div', 'htmlAttributes'=>['id'=>':id']];
		$this->BootstrapHelperEntity->create(['id'=>'test123','content'=>'foo']);
		$this->assertEquals('test123', $this->BootstrapHelperEntity->id);
		$this->assertTag(['tag'=>'div','attributes'=>['id'=>'test123']], $this->BootstrapHelperEntity->toString(),"Attribute::id should be set by the options array.");
	}

	public function testParentNode(){
		$Controller = new Controller();
		$View = new View($Controller);
		$a = new BootstrapHelperEntity($View);

		$a->id = 'Foo';
		$this->BootstrapHelperEntity->setParentNode($a);
		$this->assertEquals('Foo', $this->BootstrapHelperEntity->getParentNode()->id, 'Parent Node not setting reference correctly. Could not read parentNode::id');

		$a->id = 'Bar';
		$this->assertEquals('Bar', $this->BootstrapHelperEntity->getParentNode()->id, 'Parent Node not setting reference correctly. parentNode::id does not match after being changed by the parentNode.');
	}

	public function testEmpty(){
		$this->assertEquals('', $this->BootstrapHelperEntity->toString(), 'Entities should return a blank string when they have not been created. This is to allow more advanced collections to automatically \'ignore\' un-created peices of themselves'); 

		$this->BootstrapHelperEntity->create('test');
		$this->assertNotEquals('', $this->BootstrapHelperEntity->toString(), 'Entity still considering itself un-created (EMPTY) after Entity::create()'); 
	}

	public function testDisposable(){

		$Controller = new Controller();
		$View = new View($Controller);

		$settings = [
			'tag'=>'p',
			'htmlAttributes'=>[
				'class'=>'foo',
				'data-target'=>'bar',
			]
		];

		$t = new BootstrapHelperEntity($View, $settings);

		$t->create('Gimme Content');

		$this->assertTag(
				[
					'tag' => 'p',
					'content' => 'Gimme Content',
					'attributes' => [
						'class' => 'foo',
						'data-target' => 'bar',
					]
				],
				$t,
				'Failed asserting $settings -> $_options conversion for disposable, pre-configured entities'
			);
	}

}?>