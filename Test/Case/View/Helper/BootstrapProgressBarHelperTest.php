<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapProgressBarHelper', 'Bootstrap.View/Helper');

class BootstrapProgressBarHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Bar = new BootstrapProgressBarHelper($View);
	}

	public function testWithDefaults(){
		
		$group = $this->Bar->add();
		$bar1 = $group->add('50 Percent', 50);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'progress'
					],
				],
			$group->toString(),
			'Failed asserting Progress Bar Group'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'progress'
					],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'div',
						'attributes'=>[
							'class' => 'progress-bar',
							]
						]
					]
				],
			$group->toString(),
			'Failed asserting Progress Bar Group contains Progress-Bar children'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'progress-bar',
					'style'=>'width: calc(100% * 50 / 100);',
					'aria-valuemin'=>0,
					'aria-valuemax'=>100,
					'aria-valuenow'=>50,
					'role'=>'progress-bar',
					],
				'content'=>'50 Percent', 
			],
			$bar1->toString(),
			'Failed asserting Progress Bar #1'
			);
	}

}
?>