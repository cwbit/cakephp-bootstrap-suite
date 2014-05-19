<?php


App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

// $newAlert = $this->BootstrapAlert->add();
class BootstrapAlertHelper extends BootstrapHelperEntityCollection{

	protected $_entityClass = 'BootstrapAlertEntity';

}


// echo $this->BootstrapAlert->add('My Title', 'My message!');
class BootstrapAlertEntity extends BootstrapHelperEntity{
	protected $_pattern = "<:tag :htmlAttributes>:dismissButton:title:content</:tag>";

	protected $_options = [
		'tag' => 'div',
		'baseClass' => 'alert alert-:context :dismissable',
		'class' => '',
		'context' => 'info',
		'dismissable' => 'alert-dismissable',
		'dismissButton'=> "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>",
		'title' => "<strong>:title</:strong> ",
		'content' => ':content',
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data) && is_string($options)){
			$this->setUnless($data, 'title', $data);
			$this->setUnless($data, 'content', $options);
			$options = [];
		}
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

}