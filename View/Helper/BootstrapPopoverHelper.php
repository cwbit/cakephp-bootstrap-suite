<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

/**
 * 
 * @package default
 */
class BootstrapPopoverHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapPopoverEntity';

}

class BootstrapPopoverEntity extends BootstrapHelperEntity{
	public $_options = [
		'htmlAttributes'=>[
			'data-animation'=>true,
			'data-html'=>true,
			'data-placement'=>'right',
			'data-selector'=>false,//or selector
			'data-trigger'=>'click', //or click, hover, focus, manual
			'data-title'=>':title',
			'data-content'=>':content',
			'data-delay'=>0, //or 'delay: { show: 500, hide: 100 }'
			'data-container'=>false, //or 'body' or any container
		]
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data)):
			if(is_string($options)):
				$this->setUnless($data,'title',$data);
				$this->setUnless($data,'content',$options);
				$options = [];
			else:
				$this->setUnless($data,'content',$data);
			endif;
		endif;

		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

	public function getAttributes(){
		$options = $this->options();

		if(!$this->isDirty($options,'htmlAttributes.data-title',$emptyIsDirty = false)):
			unset($options['htmlAttributes']['data-title']);
		endif;

		if(!$this->isDirty($options,'htmlAttributes.data-content',$emptyIsDirty = false)):
			unset($options['htmlAttributes']['data-content']);
		else:
			$options['htmlAttributes']['data-content'] = htmlentities($options['htmlAttributes']['data-content'],ENT_QUOTES);
		endif;

		return ['htmlAttributes'=>$options['htmlAttributes']];
	}

	public function __toString(){
		$a = $this->getAttributes();
		$this->setHtmlAttributes($a);		
		return $a['htmlAttributes'];
	}

}


// App::uses('BootstrapHelper','Bootstrap.View/Helper');

// class BootstrapPopoverHelper extends BootstrapHelper{
	
// 	public $helpers = ['Bootstrap.Button'];
// 	public $_options = [
// 			'popover'=>[
// 				// 'label'=>':label',
// 				// 'tag'=>'button',
// 				'htmlAttributes'=>[
// 					'data-animation'=>true,
// 					'data-html'=>true,
// 					'data-placement'=>'right',
// 					'data-selector'=>false,//or selector
// 					'data-trigger'=>'click', //or click, hover, focus, manual
// 					'data-title'=>':title',
// 					'data-content'=>':content',
// 					'data-delay'=>0, //or 'delay: { show: 500, hide: 100 }'
// 					'data-container'=>false, //or 'body' or any container
// 				]
// 			]
// 		];

// 	/**
// 	 * Creates a popover which can actually be added to the htmlAttributes of any other element
// 	 * @param type $data 
// 	 * @param type $options 
// 	 * @return type
// 	 */
// 	public function attributes($data, $options = []){
// 		if(is_string($data)):
// 			if(is_string($options)):
// 				$this->setUnless($data,'title',$data);
// 				$this->setUnless($data,'content',$options);
// 				$options = [];
// 			else:
// 				$this->setUnless($data,'content',$data);
// 			endif;
// 		endif;

// 		$this->mergeOptions('popover',$options);
// 		$this->insertData($options, $data);

// 		if(!$this->isDirty($options,'htmlAttributes.data-title',$emptyIsDirty = false)):
// 			unset($options['htmlAttributes']['data-title']);
// 		endif;		

// 		if(!$this->isDirty($options,'htmlAttributes.data-content',$emptyIsDirty = false)):
// 			unset($options['htmlAttributes']['data-content']);
// 		else:
// 			$options['htmlAttributes']['data-content'] = htmlentities($options['htmlAttributes']['data-content'],ENT_QUOTES);
// 		endif;

// 		return $options['htmlAttributes'];//$this->Button->button($options);

// 	}


// }
?>