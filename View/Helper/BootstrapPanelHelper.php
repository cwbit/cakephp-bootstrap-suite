<?php
App::uses('BootstrapHelper', 'Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapPanelHelper extends BootstrapHelperEntityCollection{
	protected $_entityClass = 'BootstrapPanelEntity';
}

class BootstrapPanelEntity extends BootstrapHelperEntity{
	public $Header = null;
	public $Body = null;
	public $Footer = null;

	protected $_options = [
		'tag'=>'div',
		'baseClass'=> 'panel panel-:context',
		'context'=>'default',
	];

	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Header = new BootstrapPanelHeaderEntity($view, $settings);
        $this->Body = new BootstrapPanelBodyEntity($view, $settings);
        $this->Footer = new BootstrapPanelFooterEntity($view, $settings);
    }

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

class BootstrapPanelHeaderEntity extends BootstrapHelperEntity{
	public $Title = null;

	protected $_options = [
		'tag'=>'div',
		'baseClass'=>'panel-heading',
	];

	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Title = new BootstrapPanelTitleEntity($view, $settings);
    }

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(isset($data['title'])):
			$this->Title->create($data['title']);
		endif;
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

	public function __toString(){
		$result = [];
    	$result[] = $this->Title->toString(); #inject TITLE into content

    	$options = $this->options();
    	$this->setUnless($options, $this->_contentToken, '');
    	$options[$this->_contentToken] .= implode(PHP_EOL, $result);

    	$this->options($options);

    	return parent::__toString();
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