<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

class BootstrapHelperEntity extends BootstrapHelper{

	/**
	 * placeholder for View (from __construct) used to instantiate subclasses by default
	 * @var View 
	 */
	protected $_view = null;

	/**
	 * placeholder for settings (from __construct) used to instantiate subclasses by default
	 * @var array 
	 */
	protected $_settings = null;
	/**
	 * replacement pattern (see String::insert) used by the __toString() function
	 * @var string
	 */
	protected $_pattern = "<:tag :htmlAttributes>:content</:tag>";
	/**
	 * Default content token for the entity. Should exist in $this->_pattern. Will be automatically used as the default for create() if is_string($data).
	 * Saves you from having to specify such a standard thing for each entity.
	 * @var string
	 */
	protected $_contentToken = 'content';
	/**
	 * Flag indicating whether or not the entity has had its create() method called.
	 * When set to FALSE, __toString will return '' effectively allowing more complex entities
	 * to have peices (sub-entities) that can be safely ignored if not used.
	 * For example a Table entity that shouldn't print <tfoot> tags unless the footer has actually been set
	 * @var bool
	 */
	protected $_wasCreated = false;
	/**
	 * Used to track who the parent entity is (if any) of the current entity
	 * Especially useful in more advanced entities where the id of the parent is used to seed the id of the children
	 * (e.g. where a trigger should activate another sibling)
	 * @var BootstrapEntityHelper
	 */
	protected $_parentNode = null; 

	/**
	 * ID of this entity
	 * @var string
	 */
	public $id = null;
	/**
	 * Modified, collated version of $this->_options
	 * @var array
	 */
	public $options = [];
	/**
	 * Data passed in from create()
	 * @var mixed
	 */
	public $data = '';

	/**
	 * Contstructs the class with the given View and settings
	 * Also merges $settings into the root $_options - useful for Entities that are 'disposable' (are created at run-time)
	 * @param View $view 
	 * @param mixed $settings 
	 * @return BootstrapHelperEntity
	 */
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->_view = $view;
		$this->_settings = $settings;

		#things passed to $settings can be used to seed $_options
		#useful for disposable entity classes
		if(!empty($settings) && is_array($settings)):
			$this->mergeOptions(null,$settings);
			$this->_options = $settings;
		endif;

		return $this;
    }

    /**
     * Publicly exposed way to convert $this into it's string representation. Calls __toString().
     * IMPORTANT: This should never be overridden. Override __toString() instead.
     * @return string
     */
    public function toString(){ return (string) $this; }    

    /**
     * Converts an entity into it's string representation. Uses $this->_pattern, $this->_options and $this->_data
     * Returns '' if $this->create() or similar has not been called (meaning the entity should be ignored).
     * @return string
     */
    public function __toString(){
    	#if create() was never called, then this entity should be considered EMPTY
    	if(!$this->_wasCreated):
    		return '';
    	endif;
    	
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

    /**
     * Used to keep track of this entities parent node (tree parent, not class inheritance)
     * @param  BootstrapHelperEntity &$object 
     * @return void
     */
    public function setParentNode(BootstrapHelperEntity &$object){  //is this proper systax to keep the POINTER only?
    	$this->_parentNode =& $object;
    }
    /**
     * 'get' method for ParentNode 
     * @return BootstrapHelperEntity
     */
    public function getParentNode(){ return $this->_parentNode; }

    /**
     * get/set method for $options 
     * @param array $options 
     * @return array
     */
	public function options($options = null){ 
		if(!is_null($options)):
			$this->options = $options;
		endif;
		return $this->options;
	}
    
    /**
     * get/set method for $data 
     * @param array $data 
     * @return array
     */	
	public function data($data = null){ 
		if(!is_null($data)):
			$this->data = $data;
		endif;
		return $this->data;
	}

	/**
	 * Merges class default $_options with user specified $options.
	 * Then takes $data inserts (using modified String::insert) into $options
	 * 
	 * keyRemaps allow data key remaps e.g. 'name'-->'content' in cases where model $data is passed directly
	 * and needs to be changed on the fly.
	 * 
	 * valueRemaps allow value key remaps e.g. 'pass_key'='123'-->'pass_key'=>'OBSCURED' in cases where model $data is passed directly (i.e. from a query)
	 * and needs to be changed on the fly (i.e. for purposes of an entity)
	 * 
	 * If no ID is specified in $data or $options, one will be automatically generated
	 * 
	 * $options and $data are stored in $this->options and $this->data respectively for later use (e.g. by __toString())
	 * 
	 * @param mixed $data 
	 * @param mixed $options 
	 * @param bool|array $keyRemaps false if none, otherwise array
	 * @param bool|array $valueRemaps false if none, otherwise array
	 * @return BootstrapHelperEntity
	 */
	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		#set flag to explicity indicate the entity was created (see __toString() for where this is used)
		$this->_wasCreated = true;

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