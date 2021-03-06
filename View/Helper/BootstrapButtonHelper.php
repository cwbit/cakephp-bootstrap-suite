<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

// class BootstrapButtonHelper extends BootstrapHelperEntityCollection{
// 	protected $_entityClass = 'BootstrapButtonEntity';
// }

class BootstrapButtonEntity extends BootstrapHelperEntity{
	protected $_pattern = "<:tag :htmlAttributes>:prefix:label:suffix</:tag>";
	protected $_contentToken = 'label';

	protected $_options = [
		'tag' => 'button',
		'baseClass' => 'btn btn-:context btn-:size btn-:block :disabled :active',
		'context' => 'default',
		'size' => 'md',
		'disabled' => '', # set to 'disabled' to disable
		'block' => 'noblock', #set to 'block' for block width
		'active' => '',
		'label' => ':label',
		'prefix' => '', #allows for injection of things like glyphicons
		'suffix' => '', #or dropdown carets
		'htmlAttributes'=>[
			'id' => ':id',
			'role' => 'button',
			'type' =>'button',
			'onclick'=>'window.location.assign(":link")'
			//'data-toggle'=>'',
		],
	
	];

	public function __toString(){
		$o = $this->options();

		if(!$this->isDirty($o, 'htmlAttributes.onclick', $emptyIsDirty = false)):
			unset($o['htmlAttributes']['onclick']);
		endif;

		$this->options($o);

		return parent::__toString();
	}
}

class BootstrapButtonHelper extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapButtonEntity';
	protected $_options = [
		'baseClass' => 'btn-group btn-group-:size btn-group-:orientation',
		'tag' => 'div',
		'size' => 'md',
		'orientation' => 'horizontal',
		];
}

	// if($this->isDirty($options,'link')):
	// 				$options['link'] = "onclick=\"window.location.assign('{$options['link']}')\"";
	// 			else:
	// 				$options['link'] = '';
	// 			endif;
	// 			if(!$this->isDirty($options,'id')):
	// 				$options['id'] = $this->generateId();
	// 			endif;

	// 			$result .= $this->safeInsertData("<:tag id=':id' class=':class' :htmlAttributes :link>", $options);
	// 			$result .= $options['prefix'];
	// 			$result .= $options['label'];
	// 			$result .= $options['suffix'];
	// 			$result .= $this->safeInsertData("</:tag>",$options);




?>