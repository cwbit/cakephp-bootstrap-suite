<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperMultipartEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

/**
 * 
 * @package default
 */
class BootstrapTableHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapTableEntity';

}

class BootstrapTableEntity extends BootstrapHelperMultipartEntity{
	protected $_options = [
		'tag'		=> 'table',
		'baseClass' =>'table table-:hover table-:striped table-:bordered table-:condensed',
		'striped' =>'striped',
		'bordered' =>'notbordered', #set to 'bordered' to use
		'hover' =>'hover',
		'condensed' =>'notcondensed', #set to 'condensed' to use
		'content' => ':content',
	];

	protected $parts = [
		'Header' => 'BootstrapTableHeaderCollection',
		'Body' => 'BootstrapTableBodyCollection',
		'Footer' => 'BootstrapTableFooterCollection',
		];

}

class BootstrapTableRowCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableCellEntity';
	protected $_options = [
		'tag' => 'tr',
	];
}
		class BootstrapTableCellEntity extends BootstrapHelperEntity{
			protected $_options = [
				'tag' => 'td',
			];
		}

class BootstrapTableHeaderCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableHeaderRowCollection';
	protected $_options = [
		'tag' => 'thead',
	];
}
		/* Table header row/cell are just specialized versions of normal */
		class BootstrapTableHeaderRowCollection extends BootstrapTableRowCollection{
			protected $_entityClass = 'BootstrapTableHeaderCellEntity';
		}

				class BootstrapTableHeaderCellEntity extends BootstrapTableCellEntity{
					protected $_options = [
						'tag' => 'th',
					];
				}

class BootstrapTableBodyCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableRowCollection';
	protected $_options = [
		'tag' => 'tbody',
	];
}

class BootstrapTableFooterCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableRowCollection';
	protected $_options = [
		'tag' => 'tfoot',
	];

}






?>