<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');

/**
 * This is a special type of entity class that is made up of 'parts'
 * It is not the same as an EntityCollection because it is made up of dissimilar parts
 * 
 * e.g. A collection of Tabs would be an Entity Collection
 * but something like a Jumbotron or Table that is made up of smaller entites (Title, Body, Headers, etc) would be a multipart entity
 * 
 * It contains convenience functions for handling routine multipart behaviour
 */
class BootstrapHelperMultipartEntity extends BootstrapHelperEntity{
	
	/**
	 * @property an associative array of 
	 *  '{PartName}' => '{Part Class}'
	 * 	e.g. 'Title' => 'BootstrapHelperEntity';
	 * 
	 * If a Part Class is not explicitly specified it will default to $_defaultPartClass
	 */
	protected $parts = [];

	/**
	 * @property class the $parts will default to if not specified
	 */
	protected $_defaultPartClass = 'BootstrapHelperEntity';

	/**
	 * Automagic constructor for Multipart Entity classes that will loop through $parts and instantiate entity part classes.
	 * 
	 * e.g. If given 
	 * 		$this->parts = array('Image'=>'BootstrapThumbnailImageEntity', 'Caption');
	 * it would automatically do the following
	 *  	$this->Image = new BootstrapThumbnailImageEntity($view, $settings);
	 *		$this->Caption = new BootstrapHelperEntity($view, $settings);
	 * 
	 * @param View $view 
	 * @param array $settings 
	 * @return void
	 */
	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);

        foreach($this->parts as $key => $value):
        	if(is_integer($key) && is_string($value)):
        		$this->{$value} = new $this->_defaultPartClass($view, $settings);
                $this->{$value}->setParentNode($this);
        	elseif(is_string($key) && is_string($value)):
        		$this->{$key} = new $value($view, $settings);
                $this->{$key}->setParentNode($this);
        	else:
        		throw new BootstrapFrameworkException('Malformed Multipart for Class '.get_class().'. Was given ['.(string) $key.']=>['.(string) $value.']');
        	endif;
        endforeach;

    }

    /**
     * __toString() override for Multipart entities that will loop through it's component parts and get thier string values
     * before adding it to the content array and calling parent::__toString()
     * 
     * @return string
     */
    public function __toString(){
    	$result = [];

    	foreach($this->parts as $key => $value):
    		if(is_integer($key) && is_string($value)):
        		$result[] = (string) $this->{$value};
        	elseif(is_string($key) && is_string($value)):
        		$result[] = (string) $this->{$key};
        	endif;
    	endforeach;
    	
    	$options = $this->options();
    	$options[$this->_contentToken] = implode(PHP_EOL, $result);

    	$this->options($options);

    	return parent::__toString();
    }

    /*
     * Override for parent::create() that creates() all the child parts as well
     * @param type $data 
     * @param type $options 
     * @param type $keyRemaps 
     * @param type $valueRemaps 
     * @return object
     *
    public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
        foreach($this->parts as $key => $value):
            if(is_integer($key) && is_string($value)):
                $this->{$value}->create($data, $options, $keyRemaps, $valueRemaps);
            elseif(is_string($key) && is_string($value)):
                $this->{$key}->create($data, $options, $keyRemaps, $valueRemaps);
            endif;
        endforeach;
        return parent::create($data, $options, $keyRemaps, $valueRemaps);
    }*/

}?>