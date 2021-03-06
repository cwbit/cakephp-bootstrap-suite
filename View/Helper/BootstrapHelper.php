<?php

App::uses('AppHelper','View/Helper');

class BootstrapFrameworkException extends Exception{}

class BootstrapHelper extends AppHelper{

//	public $helpers = ['Bootstrap.BootstrapHtml'];

	protected $fallbackIndex = 'description';

	protected $_currentOptionIndex = '';

	/**
	 * @property $_options holder for Helper options
	 */ 
	protected $_options = [];

	protected $_result = '';

	public function __toString(){ return '';}

	protected static function stringInsert(&$value, $key, $data){
		$value = String::insert($value, $data);
	}

	protected function mergeOptions($index = null, &$options = [], $force = false){
		$o = $this->_options;
		if($index===null):
			$options = array_replace_recursive($o, $options);
		elseif(is_string($index)):
			$options = array_replace_recursive($o[$index], $options);
			$this->_currentOptionIndex = $index;
		elseif(is_array($index)):
			$options = array_replace_recursive($index, $options);
		endif;
	}

	protected function mergeClasses(&$data){
		$this->setUnless($data,'class','');
		$this->setUnless($data,'baseClass','');
		$data['class'] .= ' '.$data['baseClass'];
		unset($data['baseClass']);
	}

	public function insertData(&$options, $data){
		if(is_array($options)):
			array_walk_recursive($options,['self','stringInsert'],$data);
		elseif(is_string($options)):
			if(isset($data['baseClass'])):
				$this->mergeClasses($data);
			endif;
			if(stripos($options, ':htmlAttributes') !== false):
				if((stripos($options, ':class') === false) && isset($data['class'])):
					$data['htmlAttributes']['class'] = (isset($data['htmlAttributes']['class'])) ? $data['htmlAttributes']['class'] + $data['class'] : $data['class'];
				endif;
				$this->setHtmlAttributes($data);
			endif;
			$this->stringInsert($options,[],$data);
		endif;

	}

	/* wrapper for insertData that doesn't passthrough the original options by reference */
	public function safeInsertData($options, $data){
		$this->insertData($options, $data);
		return $options;
	}

	protected function isDirty($data, $hashPath, $emptyIsDirty = true){
		
		#something is dirty if it has been changed from the original
		$original = Hash::extract($this->_options, ($this->_currentOptionIndex != '') ? $this->_currentOptionIndex.".".$hashPath : $hashPath);
		$current = (is_string($data)) ? (array) $data : Hash::extract($data, $hashPath);

		if ($current == [''] && $emptyIsDirty):
			return false;
		elseif ($current == $original):
			return false;
		else:
			return true;
		endif;

	}

	/**
	 * Wrapper for setUnless that is specifically used to parse a function's $data param.
	 * If $data is not an array the function will call setUnless.
	 * @param type &$data 
	 * @param type $index 
	 * @param type $value 
	 * @return type
	 */
	protected function setDefault(&$data, $index, $value = null, $keepDataOnConversion = false){
		if(!is_array($data)):
			$this->setUnless($data, $index, $value, $keepDataOnConversion);
		endif;
	}

	/**
	 * Wrapper for Helper->_parseAttributes that will first create, then populate an 'htmlAttributes' index in the given array
	 * @param type &$options 
	 * @param type $exclude 
	 * @param type $insertBefore 
	 * @param type $insertAfter 
	 * @return type
	 */
	protected function setHtmlAttributes(&$options, $exclude = null, $insertBefore = '', $insertAfter = null){
		$this->setUnless($options,'htmlAttributes',[]);
		$options['htmlAttributes'] = $this->_parseAttributes($options['htmlAttributes'], $exclude, $insertBefore, $insertAfter);
		return $options;
	}

	/**
	 * If $var has no value it will be replaced with $key. 
	 * If $key and $value are set then $var[$key] will default to $value if not set
	 * @param mixed &$var 
	 * @param string or mixed $key 
	 * @param mixed $value 
	 * @param bool $keepDataOnConversion wether or not $var should be set to [] first before injecting $var[$key]
	 */
	protected function setUnless(&$var, $key, $value = null, $keepDataOnConversion = false){
		if(!is_null($value)): //allow override to check indicies
			if (!is_array($var)): //convert to array if required
				$var = ($keepDataOnConversion) ? (array)$var : [];
			endif;
			if(!isset($var[$key]) || is_null($var[$key])):
				$var[$key] = $value;
			endif;

		elseif(!isset($var) || is_null($var)):
			$var = $key;
		endif;
	}

	/**
	 * Creates a random #id for html elements in format ':index-:rand'
	 * where :index is the currentOption index and :rand is a random intval
	 * @return string
	 */
	protected function generateId(){
		$prefix = ($this->_currentOptionIndex != '') ? $this->_currentOptionIndex.'-' : '';
		return $prefix.intval(mt_rand());
	}
	
}
?>