<?php


App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapGlyphiconHelper', 'Bootstrap.View/Helper');

class BootstrapGlypiconHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Glyph = new BootstrapGlyphiconHelper($View);
	}

	public function testWithDefaults(){
		$a = $this->Glyph->add('cloud');
		
		$this->assertTag(
			[
				'tag'=>'span', 
				'attributes' => [
					'class'=>'glyphicon glyphicon-cloud'
					],
				],
			$a->toString()
			);
	}

	public function testWithOverride(){
		$a = $this->Glyph->add('minus',['tag'=>'div']);
		
		$this->assertTag(
			[
				'tag'=>'div', 
				'attributes' => [
					'class'=>'glyphicon glyphicon-minus'
					],
				],
			$a->toString()
			);
	}

}
?>