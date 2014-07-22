<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapButtonHelper extends BootstrapHelperEntityCollection{

	protected $_entityClass = 'BootstrapButtonEntity';

}

class BootstrapButtonEntity extends BootstrapHelperEntity{
	protected $_pattern = "<:tag :htmlAttributes>:labelPrefix:label:labelSuffix</:tag>";
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
		'labelPrefix' => '', #allows for injection of things like glyphicons
		'labelSuffix' => '', #or dropdown carets
		'htmlAttributes'=>[
			'id' => ':id',
			'role' => 'button',
			'type' =>'button',
			'onclick'=>'window.location.assign(":link")'
			//'data-toggle'=>'',
		],
	
	];
}

/* not used yet */
class BootstrapButtonGroupCollection extends BootstrapHelperWrappedEntityCollection{
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
	// 			$result .= $options['labelPrefix'];
	// 			$result .= $options['label'];
	// 			$result .= $options['labelSuffix'];
	// 			$result .= $this->safeInsertData("</:tag>",$options);




?>