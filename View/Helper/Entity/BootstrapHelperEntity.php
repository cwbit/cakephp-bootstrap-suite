<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

class BootstrapHelperEntity extends BootstrapHelper{
	protected $_view = null;
	protected $_settings = null;
	protected $_pattern = "<:tag :htmlAttributes>:content</:tag>";
	protected $_contentToken = 'content';
	/**
	 * @property can be used to retain node links 
	 */
	protected $_parentNode = null; 

	
	public $id = null;
	public $options = [];
	public $data = '';

	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->_view = $view;
        $this->_settings = $settings;
		return $this;
    }

    public function toString(){ return (string) $this; }    

    public function __toString(){
    	// $pattern = str_replace('contentToken', $this->_contentToken, $this->_pattern);
    	$pattern = $this->_pattern;
    	$options = $this->options();
    	if(stripos($pattern, ":".$this->_contentToken) !== false):
    		if(!isset($options[$this->_contentToken])):
    			if(isset($this->data[$this->_contentToken])):
    				$options[$this->_contentToken] = $this->data[$this->_contentToken];
    			else:
    				$options[$this->_contentToken] = '';
    			endif;
    		endif;
    	endif;
    	return $this->safeInsertData($pattern,$options);
    }

    public function setParentNode(BootstrapHelperEntity &$object){  //is this proper systax to keep the POINTER only?
    	$this->_parentNode =& $object;
    }
    public function getParentNode(){ return $this->_parentNode; }

	public function options($options = null){ 
		if(!is_null($options)):
			$this->options = $options;
		endif;
		return $this->options;
	}
	
	public function data($data = null){ 
		if(!is_null($data)):
			$this->data = $data;
		endif;
		return $this->data;
	}

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		#merge the passed options with the entity's defaults
		$this->mergeOptions(null,$options);
		#allow data key remaps e.g. 'name'-->'content' in cases where model $data is passed directly
		# and needs to be changed on the fly
		if($keyRemaps):
			foreach(array_intersect_key($keyRemaps, $data) as $oldKey => $newKey):
				$temp = $data[$oldKey];
				$data[$newKey] = $temp;
				unset($temp, $data[$oldKey]);
			endforeach;
		endif;

		#allow value key remaps e.g. 'pass_key'='123'-->'pass_key'=>'OBSCURED' in cases where model $data is passed directly (i.e. from a query)
		# and needs to be changed on the fly (i.e. for purposes of an entity)
		if($valueRemaps):
			foreach($valueRemaps as $path => $value):
				$data = Hash::insert($data,$path,$value);
			endforeach;
		endif;

		#default if $data is string e.g. $data['content'] = $data
		$this->setDefault($data, $this->_contentToken, $data);

		#insert the $data into the $options (uses String::insert)
		$this->insertData($data, $data);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		#store results in $this for other functions to use (namely, __toString)
		if(isset($options['id'])):
			$this->id = $options['id'];
		elseif(isset($data['id'])):
			$this->id = $data['id'];
		else:
			$this->id = $this->generateId();
		endif;
		
		$this->options($options);
		$this->data($data);

		return $this;
	}

	
}
?>