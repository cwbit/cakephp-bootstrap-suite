<?php


App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapPopoverHelper', 'Bootstrap.View/Helper');

class BootstrapPopoverHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Popover = new BootstrapPopoverHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Popover->add('My Title', 'My Glorious Body!');

		#popovers are not a DOM element, but are a set of attributes
		#that can be attached to any element on creation
		$b = "<div :htmlAttributes></div>";
		$b = $this->Popover->safeInsertData($b, $a->getAttributes());
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					// 'data-animation'=>true,
					// 'data-html'=>true,
					// 'data-placement'=>'right',
					// 'data-selector'=>false,
					// 'data-trigger'=>'click', 
					'data-title'=>'My Title',
					'data-content'=>'My Glorious Body!',
					// 'data-delay'=>0, 
					// 'data-container'=>false,
					],
				],
			$b,
			'Failed asserting Popover attributes'
			);

		#should be same as above, but different way of doing it
		$b = "<div {$a->toString()}></div>";
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					// 'data-animation'=>true,
					// 'data-html'=>true,
					// 'data-placement'=>'right',
					// 'data-selector'=>false,
					// 'data-trigger'=>'click', 
					'data-title'=>'My Title',
					'data-content'=>'My Glorious Body!',
					// 'data-delay'=>0, 
					// 'data-container'=>false,
					],
				],
			$b,
			'Failed asserting Popover attributes using toString() direct injection'
			);
	}

}
?>