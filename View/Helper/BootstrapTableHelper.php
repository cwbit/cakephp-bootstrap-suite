<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

/**
 * 
 * @package default
 */
class BootstrapTableHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapTableEntity';

}

class BootstrapTableEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag'		=> 'table',
		'baseClass' =>'table table-:hover table-:striped table-:bordered table-:condensed',
		'striped' =>'striped',
		'bordered' =>'notbordered', #set to 'bordered' to use
		'hover' =>'hover',
		'condensed' =>'notcondensed', #set to 'condensed' to use
		'content' => ':content',
	];

	public $Header = null;
	public $Body = null;
	public $Footer = null;

	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Body = new BootstrapTableBodyCollection($view, $settings);
        $this->Header = new BootstrapTableHeaderCollection($view, $settings);
        $this->Footer = new BootstrapTableFooterCollection($view, $settings);
    }

    public function __toString(){
    	$result = [];
    	$result[] = $this->Header->toString();
    	$result[] = $this->Body->toString();
    	$result[] = $this->Footer->toString();

    	$options = $this->options();
    	$options[$this->_contentToken] = implode(PHP_EOL, $result);

    	$this->options($options);

    	return parent::__toString();
    }

}

class BootstrapTableHeaderCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableHeaderRowCollection';
	protected $_options = [
		'tag' => 'thead',
	];
}

class BootstrapTableBodyCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableRowCollection';
	protected $_options = [
		'tag' => 'tbody',
	];
}

class BootstrapTableRowCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableCellEntity';
	protected $_options = [
		'tag' => 'tr',
	];
}

class BootstrapTableFooterCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapTableRowCollection';
	protected $_options = [
		'tag' => 'tfoot',
	];

}

class BootstrapTableCellEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag' => 'td',
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

?>