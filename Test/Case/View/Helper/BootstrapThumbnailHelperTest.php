<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapThumbnailHelper', 'Bootstrap.View/Helper');

class BootstrapThumbnailHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Thumbnail = new BootstrapThumbnailHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Thumbnail->add();
		$a->Image->create('/path/to/img.png');

		$this->assertTag(
			[
				'tag' => 'div',
				'attributes' => [
					'class' => 'thumbnail',
				],
				'child' => [
					'tag' => 'img',
					'attributes'=>[
						'src' => '/path/to/img.png',
					],
				]
			],
			$a,
			'Failed asserting Thumbnail (image-only)'
			);

		$a->Caption->create('<p>Hi There!</p>');
		$a->Caption->Title->create('My Title');

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'caption'
					],
				'child' => [
					'tag' => 'p',
					'content' => 'Hi There!',
				],
			],
			$a,
			'Failed asserting Thumbnail caption'
			);

		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class' => 'caption'
					],
				'child' => [
					'tag' => 'h1',
					'content' => 'My Title',
				],
			],
			$a,
			'Failed asserting Thumbnail caption title'
			);
	}

}
?>