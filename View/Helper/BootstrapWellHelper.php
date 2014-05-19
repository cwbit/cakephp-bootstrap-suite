<?php
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
// App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapWellHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapWellEntity';
}

class BootstrapWellEntity extends BootstrapHelperEntity{
	public $_options = 	[
		'tag' => 'div',
		'baseClass' => 'well well-:size',
		'size'=>'md',
	];
}
?>