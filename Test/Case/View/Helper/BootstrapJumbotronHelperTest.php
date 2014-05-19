<?php


App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapJumbotronHelper', 'Bootstrap.View/Helper');

class BootstrapJumbotronHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Jumbotron = new BootstrapJumbotronHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Jumbotron->add('My Title', 'My Glorious Body!');
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'jumbotron container'
					],
				],
			$a->toString(),
			'Failed asserting Jumbotron class defaults'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'child'=>[
					'tag'=>'h1',
					'content'=>'My Title'
					]
				],
			$a->toString(),
			'Failed asserting Title'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'child'=>[
					'tag'=>'p',
					'content'=>'My Glorious Body!'
					]
				],
			$a->toString(),
			'Failed asserting Body'
			);


	}

}
?>