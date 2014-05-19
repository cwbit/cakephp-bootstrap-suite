<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapHelperEntityCollectionTest extends CakeTestCase{

	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->BootstrapHelperEntityCollection = new BootstrapHelperEntityCollection($View);
	}

	public function testConstruction(){
		$this->assertInstanceOf('BootstrapHelperEntityCollection', $this->BootstrapHelperEntityCollection);
	}

	public function testEmptyToString(){
		$this->assertEquals('',$this->BootstrapHelperEntityCollection->toString());
	}

	public function testAdd(){
		$e = $this->BootstrapHelperEntityCollection->add('',['tag'=>'span']);
		$this->assertInstanceOf('BootstrapHelperEntity', $e);

		$this->BootstrapHelperEntityCollection->add('',['tag'=>'span']);

		$es = $this->BootstrapHelperEntityCollection->get();

		$this->assertEquals(2, count($es));

		foreach($es as $e){
			$this->assertInstanceOf('BootstrapHelperEntity', $e);
		}

		#wrap and make sure there are two child spans.
		$result = "<div>".$this->BootstrapHelperEntityCollection->toString()."</div>";
		$this->assertTag(['tag'=>'div','children'=>['count'=>2]], $result, "Two children were set, but two were not returned.");
	}

	public function testAddMultipleByArgs(){
		$this->BootstrapHelperEntityCollection->addMultiple(
			['one',['tag'=>'span']],
			['two',['tag'=>'span']],
			['three',['tag'=>'span']],
			['four',['tag'=>'span']]
			);
		$es = $this->BootstrapHelperEntityCollection->get();

		$this->assertEquals(4, count($es));

		foreach($es as $e){
			$this->assertInstanceOf('BootstrapHelperEntity', $e);
		}

		#wrap and make sure there are two child spans.
		$result = "<div>".$this->BootstrapHelperEntityCollection->toString()."</div>";
		$this->assertTag(['tag'=>'div','children'=>['count'=>4]], $result, "Two children were set, but two were not returned.");

	}
	public function testAddMultipleByArray(){
		$this->BootstrapHelperEntityCollection->addMultiple(
			[
				['one',['tag'=>'span']],
				['two',['tag'=>'span']],
				['three',['tag'=>'span']],
				['four',['tag'=>'span']]
			]
			);
		$es = $this->BootstrapHelperEntityCollection->get();

		$this->assertEquals(4, count($es));

		foreach($es as $e){
			$this->assertInstanceOf('BootstrapHelperEntity', $e);
		}

		#wrap and make sure there are two child spans.
		$result = "<div>".$this->BootstrapHelperEntityCollection->toString()."</div>";
		$this->assertTag(['tag'=>'div','children'=>['count'=>4]], $result, "Two children were set, but two were not returned.");

	}

	public function testGet(){
		$a = $this->BootstrapHelperEntityCollection->add(['id'=>'foo']);
		$b = $this->BootstrapHelperEntityCollection->add(['id'=>'bar']);

		$aTest = $this->BootstrapHelperEntityCollection->get('foo');
		$this->assertInstanceOf('BootstrapHelperEntity', $aTest);
				
		$bTest = $this->BootstrapHelperEntityCollection->get('bar');
		$this->assertInstanceOf('BootstrapHelperEntity', $bTest);

		$this->assertEquals('foo', $aTest->id);
		$this->assertEquals('bar', $bTest->id);

		$a->data = ['test'];
		$this->assertEquals($aTest->data, $a->data);

		$this->assertEquals(2, count($this->BootstrapHelperEntityCollection->get()));

	}

	public function testSet(){
		$a = $this->BootstrapHelperEntityCollection->add(['id'=>'foo']);
		
		$b = $a;
		$b->id = 'bar';

		$this->BootstrapHelperEntityCollection->set('bar', $b);
				
		$bTest = $this->BootstrapHelperEntityCollection->get('bar');
		$this->assertInstanceOf('BootstrapHelperEntity', $bTest);

		$this->assertEquals('bar', $bTest->id);

		$this->assertEquals(2, count($this->BootstrapHelperEntityCollection->get()));

		$this->setExpectedException('PHPUnit_Framework_Error');
		$this->BootstrapHelperEntityCollection->set('foo','failure-at-the-cave');

	}

	public function testDestroy(){
		$this->BootstrapHelperEntityCollection->add(['id'=>'foo']);
		$this->BootstrapHelperEntityCollection->add(['id'=>'bar']);

		$a = $this->BootstrapHelperEntityCollection->get('bar');
		$this->assertEquals('bar', $a->id);
		$this->assertEquals(2, count($this->BootstrapHelperEntityCollection->get()));

		$this->BootstrapHelperEntityCollection->delete('bar');
		$this->assertEquals(1, count($this->BootstrapHelperEntityCollection->get()));

		$this->assertNull($this->BootstrapHelperEntityCollection->get('bar'));

		$this->BootstrapHelperEntityCollection->delete();
		$this->assertEquals([], $this->BootstrapHelperEntityCollection->get());

	}

	public function testArrayAccess(){

		$a = $this->BootstrapHelperEntityCollection->add(['id'=>'foo']);
		$b = $this->BootstrapHelperEntityCollection->add(['id'=>'bar']);

		$this->assertEquals('foo', $this->BootstrapHelperEntityCollection['foo']->id);
		$this->assertEquals(2, count($this->BootstrapHelperEntityCollection[null]));

		$this->BootstrapHelperEntityCollection['foo'] = $b;
		$this->assertEquals('bar', $this->BootstrapHelperEntityCollection['foo']->id);

		$this->BootstrapHelperEntityCollection[null] = $b;
		$this->assertInstanceOf('BootstrapHelperEntity', $b, 'Reference to entity destroyed by ArrayAccess::unset on Collection');
		$this->assertEquals(3, count($this->BootstrapHelperEntityCollection[null]), 'ArrayAccess::set[null] does not appear to have added the new entity.' );
		$this->assertEquals('bar', $this->BootstrapHelperEntityCollection->get(0)->id, 'BootstrapHelperEntityCollection failed to properly re-assign its entity collection array.');
		$this->assertTrue(isset($this->BootstrapHelperEntityCollection[0]), 'Collection[\'test\'] does not appear to be set.');
		$this->assertInstanceOf('BootstrapHelperEntity', $this->BootstrapHelperEntityCollection[0]);

		unset($this->BootstrapHelperEntityCollection[0]);
		$this->assertEquals(2, count($this->BootstrapHelperEntityCollection[null]), 'ArrayAccess::unset[$id] did not properly unset the element.' );

	}

}?>