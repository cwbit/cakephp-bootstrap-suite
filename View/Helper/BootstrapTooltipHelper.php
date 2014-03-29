<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class BootstrapTooltipHelper extends BootstrapHelper{
	
	public $helpers = ['Bootstrap.Button'];
	public $_options = [
			'tooltip'=>[
				'htmlAttributes'=>[
					'data-animation'=>true,
					'data-html'=>true,
					'data-placement'=>'auto', //top, bottom, left, right, auto
					'data-selector'=>false, //or css selector
					'data-trigger'=>'hover focus', //or click, hover, focus, manual
					'data-title'=>':title',
					// 'data-content'=>':content',
					'data-delay'=>0, //or 'delay: { show: 500, hide: 100 }'
					'data-container'=>false, //or 'body' or any container
				]
			]
		];

	/**
	 * Tooltips can be added to any element so this just returns the Attributes
	 * @param type $data 
	 * @param type $options 
	 * @return type
	 */
	public function attributes($data, $options = []){
		if(is_string($data)):
			// if(is_string($options)):
			// 	$this->setUnless($data,'title',$data);
			// 	// $this->setUnless($data,'content',$options);
			// 	$options = [];
			// else:
				$this->setUnless($data,'content',$data);
			// endif;
		endif;

		$this->mergeOptions('popover',$options);
		$this->insertData($options, $data);

		if(!$this->isDirty($options,'htmlAttributes.data-title',$emptyIsDirty = false)):
			unset($options['htmlAttributes']['data-title']);
		endif;		

		// if(!$this->isDirty($options,'htmlAttributes.data-content',$emptyIsDirty = false)):
		// 	unset($options['htmlAttributes']['data-content']);
		// endif;

		#is there ever a case where you DONT want the content to be escaped?
		$options['htmlAttributes']['data-title'] = htmlentities($options['htmlAttributes']['data-title'],ENT_QUOTES);

		return $options['htmlAttributes'];

	}


}?>