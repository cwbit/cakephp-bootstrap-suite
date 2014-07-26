<?php
App::uses('BootstrapHelper', 'Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperMultipartEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapPanelHelper extends BootstrapHelperEntityCollection{
	protected $_entityClass = 'BootstrapPanelEntity';
}

class BootstrapPanelEntity extends BootstrapHelperMultipartEntity{

	protected $_options = [
		'tag'=>'div',
		'baseClass'=> 'panel panel-:context',
		'context'=>'default',
	];

	protected $parts = [
		'Header' => 'BootstrapPanelHeaderEntity',
		'Body' => 'BootstrapPanelBodyEntity',
		'Footer' => 'BootstrapPanelFooterEntity'
		];


    public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
    	if(is_string($data)):
    		if(is_string($options)):
	    		$this->setUnless($data, 'title', $data);
	    		$this->setUnless($data, 'body', $options);
	    		$options = [];
	    		if(is_string($keyRemaps)):
	    			$this->setUnless($data, 'footer', $keyRemaps);
	    			$keyRemaps = false;
	    		endif;
	    	else:
	    		$this->setUnless($data, 'body', $data);
	    	endif;
	    endif;

	    if(isset($data['header'])||isset($data['title'])):
	    	$this->Header->create($data, $options, $keyRemaps, $valueRemaps);
	    endif;
	    if(isset($data['body'])):
	    	$this->Body->create($data['body'], $options, $keyRemaps, $valueRemaps);
	    endif;
	    if(isset($data['footer'])):
	    	$this->Footer->create($data['footer'], $options, $keyRemaps, $valueRemaps);
	    endif;

	    return parent::create($data, $options, $keyRemaps, $valueRemaps);
    }

}

class BootstrapPanelHeaderEntity extends BootstrapHelperMultipartEntity{
	public $Title = null;

	protected $_options = [
		'tag'=>'div',
		'baseClass'=>'panel-heading',
	];

	protected $parts = [
		'Title' => 'BootstrapPanelTitleEntity',
		];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(isset($data['title'])):
			$this->Title->create($data['title']);
		endif;
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}
	
}

class BootstrapPanelTitleEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag'=>'h3',
		'baseClass'=>'panel-title',
	];
}
class BootstrapPanelBodyEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag'=>'div',
		'baseClass' => 'panel-body',
	];
}
class BootstrapPanelFooterEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag'=>'div',
		'baseClass' => 'panel-footer',
	];
}

// App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

// class BootstrapPanelHelper extends BootstrapHelper{

// 	public $_options = [
// 			'panel' =>[
// 				'tag'=>'div',
// 				'baseClass'=> 'panel panel-:context',
// 				'class'=>'',
// 				'context'=>'default',
// 				'heading'=>[
// 					'tag'=>'div',
// 					'baseClass'=>'panel-heading',
// 					'class' => '',
// 					'content'=> ':heading',
// 					'title'=>[
// 						'tag'=>'h3',
// 						'baseClass'=>'panel-title',
// 						'class'=>'',
// 						'content'=>':title'
// 					]
// 				],
// 				'body'=>[
// 					'tag'=>'div',
// 					'baseClass' => 'panel-body',
// 					'class'=> '',
// 					'content' => ':body',
// 				],
// 				'after' => ':after',
// 				'footer'=>[
// 					'tag'=>'div',
// 					'baseClass' => 'panel-body',
// 					'class'=> '',
// 					'content' => ':footer',
// 				],
// 			]
// 		];

// 	public function create($data, $options = []){
// 		if (is_string($data)):
// 			$temp = [];
// 			$temp['body'] = $data;
// 			$data = $temp;
// 		endif;

// 		$this->mergeOptions('panel',$options);
// 		$this->insertData($options, $data);
// 		$this->insertData($options, $options);

// 		$result = '';

// 		$result .= String::insert("<:tag class=':baseClass :class'>", $options);
// 		#if header (and/or title)
// 		if ($this->isDirty($options,'heading.content') || $this->isDirty($options,'heading.title.content')):
// 			$result .= String::insert("<:tag class=':baseClass :class'>", $options['heading']);
// 				if($this->isDirty($options,'heading.title.content')):
// 					$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['heading']['title']);
// 				else:
// 					$result .= String::insert(":content", $options['heading']);
// 				endif;
// 			$result .= String::insert("</:tag>", $options['heading']);
// 		endif;
// 		#if body
// 		if ($this->isDirty($options, 'body.content')):
// 			$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['body']);
// 		endif;
// 		#if table, list, etc
// 		if ($this->isDirty($options, 'after')):
// 			$result .= $options['after'];
// 		endif;
// 		#if footer
// 		if ($this->isDirty($options, 'footer.content')):
// 			$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['footer']);
// 		endif;

// 		$result .= String::insert("</:tag>", $options);

// 		return $result;

// 	}


// }
?>