<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapTabHelper', 'Bootstrap.View/Helper');

class BootstrapTabHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Tab = new BootstrapTabHelper($View);
	}

	public function testWithDefaults(){
		$tabSet = $this->Tab->add();

		$tabSet->Tabs->add('test1','FOO');
		$tabSet->Panes->add('test1','FOO CONTENT');

		$this->assertTag(
			[
				'tag'=>'ul',
				'attributes'=>[
					'class'=>'nav nav-tabs'
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'li'
					]
				]

			],
			$tabSet->Tabs->toString(),
			'Failed asserting Tab collection'
			);

		$this->assertTag(
			[
				'tag'=>'li',
				'ancestor'=>[
					'tag'=>'ul',
					'attributes'=>[
						'class'=>'nav nav-tabs'
					]
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'a',
						'content'=>'FOO',
					]
				]

			],
			$tabSet->Tabs->toString(),
			'Failed asserting Tab link element'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'div',
						'content'=>'FOO CONTENT',
					]
				]

			],
			$tabSet->Panes->toString(),
			'Failed asserting Pane collection'
			);

		$this->assertTag(
				[
					'tag'=>'a',
					'attributes'=>[
						'href'=>'#test1'
					]
				],
				$tabSet->Tabs->get('test1')->toString(),
				'Failed asserting tab target'
			);

		$this->assertTag(
				[
					'tag'=>'div',
					'attributes'=>[
						'id'=>'test1'
					],
				],
				$tabSet->Panes->get('test1')->toString(),
				'Failed asserting pane id'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Panes)');

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Panes)');

		$this->assertTag(
			[
				'tag'=>'ul',
				'attributes'=>[
					'class'=>'nav nav-tabs'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Tabs)');

	}

	public function testAddSet(){
		$tabSet = $this->Tab->add();
		$tabSet->addSet('test1', 'FOO','FOO CONTENT');

		$this->assertTag(
			[
				'tag'=>'ul',
				'attributes'=>[
					'class'=>'nav nav-tabs'
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'li'
					]
				]

			],
			$tabSet->Tabs->toString(),
			'Failed asserting Tab collection'
			);

		$this->assertTag(
			[
				'tag'=>'li',
				'ancestor'=>[
					'tag'=>'ul',
					'attributes'=>[
						'class'=>'nav nav-tabs'
					]
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'a',
						'content'=>'FOO',
					]
				]

			],
			$tabSet->Tabs->toString(),
			'Failed asserting Tab link element'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
				'children'=>[
					'count'=>1,
					'only'=>[
						'tag'=>'div',
						'content'=>'FOO CONTENT',
					]
				]

			],
			$tabSet->Panes->toString(),
			'Failed asserting Pane collection'
			);

		$this->assertTag(
				[
					'tag'=>'a',
					'attributes'=>[
						'href'=>'#test1'
					]
				],
				$tabSet->Tabs->get('test1')->toString(),
				'Failed asserting tab target'
			);

		$this->assertTag(
				[
					'tag'=>'div',
					'attributes'=>[
						'id'=>'test1'
					],
				],
				$tabSet->Panes->get('test1')->toString(),
				'Failed asserting pane id'
			);

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Panes)');

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Panes)');

		$this->assertTag(
			[
				'tag'=>'ul',
				'attributes'=>[
					'class'=>'nav nav-tabs'
				],
			], 
			$tabSet, 
			'Failed asserting tabSet::toString (Tabs)');
	}

	function testAutoActivate(){

		$tabSet = $this->Tab->add();
		$tabSet->addSet('test1', 'tab1', 'pane1');
		$tabSet->addSet('test2', 'tab2', 'pane2');
		$tabSet->addSet('test3', 'tab3', 'pane3');

		$tabSet->activate();

		$this->assertTag(
			[
				'tag'=>'li',
				'attributes'=>[
					'class'=>'active'
				],
				'child' => [
					'tag' => 'a',
					'attributes' => [
						'href' => '#test1'
					]
				],
			], 
			$tabSet, 
			'Failed asserting Tab activate()d');

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
				'child' => [
					'tag' => 'div',
					'attributes' => [
						'class'=>'tab-pane active',
						'id' => 'test1',
					]
				],
			], 
			$tabSet, 
			'Failed asserting Pane activate()d');
	
	}

	function testManualActivate(){

		$tabSet = $this->Tab->add();
		$tabSet->addSet('test1', 'tab1', 'pane1');
		$tabSet->addSet('test2', 'tab2', 'pane2');
		$tabSet->addSet('test3', 'tab3', 'pane3');
		$tabSet->addSet('test4', 'tab4', 'pane4');
		$tabSet->addSet('test5', 'tab5', 'pane5');

		$tabSet->activate('test3');

		$this->assertTag(
			[
				'tag'=>'li',
				'attributes'=>[
					'class'=>'active'
				],
				'child' => [
					'tag' => 'a',
					'attributes' => [
						'href' => '#test3'
					]
				],
			], 
			$tabSet, 
			'Failed asserting Tab activate()d');

		$this->assertTag(
			[
				'tag'=>'div',
				'attributes'=>[
					'class'=>'tab-content'
				],
				'child' => [
					'tag' => 'div',
					'attributes' => [
						'class'=>'tab-pane active',
						'id' => 'test3',
					]
				],
			], 
			$tabSet, 
			'Failed asserting Pane activate()d');
	
	}
}
?>