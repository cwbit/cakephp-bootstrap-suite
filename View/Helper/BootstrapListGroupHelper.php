<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');


/**
 * @link http://getbootstrap.com/components/#list-group
 * @package default
 */
class BootstrapListGroupHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'ListGroupCollection';
}

class ListGroupCollection extends BootstrapHelperWrappedEntityCollection{
	public $_entityClass = 'ListEntity';
	public $_options = [
		'tag' => 'ul',
		'htmlAttributes' => [
			'class' => 'list-group',
		]
	];
}

class ListEntity extends BootstrapHelperEntity{
	public $_options = [
		'tag' => 'li',
		'htmlAttributes' => [
			'class' => 'list-group-item',
		]
	];
}