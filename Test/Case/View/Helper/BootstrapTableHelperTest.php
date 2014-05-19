<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('BootstrapTableHelper', 'Bootstrap.View/Helper');

class BootstrapTableHelperTest extends CakeTestCase{
	
	public function setUp(){
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Table = new BootstrapTableHelper($View);
	}

	public function testInheritance(){
		$this->assertInstanceOf('BootstrapHelperEntityCollection', $this->Table);
	}

	public function testDefault(){
		$t = $this->Table->add(['id'=>'test']);
		$this->assertInstanceOf('BootstrapHelperEntity', $t);

		$this->assertTag(['tag'=>'table'], $t, "Table not wrapped properly. Tag::table does not exist.");
		$this->assertNotTag(['tag'=>'table','child'=>['tag'=>'thead']], $t, "Table::header should not exist, it was not specified as part of this test.");
		$this->assertNotTag(['tag'=>'table','child'=>['tag'=>'tbody']], $t, "Table::body should not exist, it was not specified as part of this test.");
		$this->assertNotTag(['tag'=>'table','child'=>['tag'=>'tfoot']], $t, "Table::footer should not exist, it was not specified as part of this test.");
	}

	public function testBasics(){
		$t = $this->Table->add(['id'=>'test']);

		$t->Header->add()->addMultiple(['foo'],['bar']);
		$t->Body->add()->addMultiple(['baz'],['bash']);
		$t->Body->add()->addMultiple(['baz2'],['bash2']);
		$t->Footer->add()->addMultiple(['goodbye'],['cruel_world']);

		$result = $t->toString();
		
		$this->assertTag(['tag'=>'table','child'=>['tag'=>'thead']], $result, 'Table::thead does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'table','child'=>['tag'=>'tbody']], $result, 'Table::tbody does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'table','child'=>['tag'=>'tfoot']], $result, 'Table::tfoot does not exist or is not properly formatted.');

		$this->assertTag(['tag'=>'thead','child'=>['tag'=>'tr'], 'children'=>['count'=>1]], $result, 'Table::thead::tr does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'tr','parent'=>['tag'=>'thead'],'child'=>['tag'=>'th'],'children'=>['count'=>2]], $result, 'Table::thead::tr does not have the correct number of children.');
		$this->assertTag(['tag'=>'th','parent'=>['tag'=>'tr'],'ancestor'=>['tag'=>'thead'],'content'=>'foo'], $result, 'First Table::thead::tr::th does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'th','parent'=>['tag'=>'tr'],'ancestor'=>['tag'=>'thead'],'content'=>'bar'], $result, 'Second Table::thead::tr::th does not exist or is not properly formatted.');

		$this->assertTag(['tag'=>'tbody','child'=>['tag'=>'tr'], 'children'=>['count'=>2]], $result, 'Table::tbody::tr (x2) does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'tr','parent'=>['tag'=>'tbody'],'child'=>['tag'=>'td'],'children'=>['count'=>2]], $result, 'Table::tbody::tr does not have the correct number of children.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tbody'],'content'=>'baz'], $result, 'Table::tbody::tr(1)::td(1) does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tbody'],'content'=>'bash'], $result, 'Table::tbody::tr(1)::td(2) does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tbody'],'content'=>'baz2'], $result, 'Table::tbody::tr(2)::td(1) does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tbody'],'content'=>'bash2'], $result, 'Table::tbody::tr(2)::td(1) does not exist or is not properly formatted.');

		$this->assertTag(['tag'=>'tfoot','child'=>['tag'=>'tr'], 'children'=>['count'=>1]], $result, 'Table::tfoot::tr does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'tr','parent'=>['tag'=>'tfoot'],'child'=>['tag'=>'td'],'children'=>['count'=>2]], $result, 'Table::tfoot::tr does not have the correct number of children.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tfoot'],'content'=>'goodbye'], $result, 'Table::tfoot::tr(1)::td(1) does not exist or is not properly formatted.');
		$this->assertTag(['tag'=>'td','ancestor'=>['tag'=>'tfoot'],'content'=>'cruel_world'], $result, 'Second Table::tfoot::tr(1)::td(2) does not exist or is not properly formatted.');


	}
}?>