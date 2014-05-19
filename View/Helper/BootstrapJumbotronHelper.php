<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

// $newJumbotron = $this->BootstrapJumbotron->add();
class BootstrapJumbotronHelper extends BootstrapHelperEntityCollection{
	protected $_entityClass = 'BootstrapJumbotronEntity'; #create jumbotron entities when add() is called
}

/**
 * Jumbotron unit is made up of Title and Body entities and is wrapped in tag with class
 * @package default
 * @example echo $this->BootstrapGlyphicon->add('Jumbo Title', 'Jumbo Content');
 * @example $specificJumbo = $this->BootstrapGlyphicon->add()->Content->data['content'] = 'My Glorious Body';
 * @example $specificJumbo = $this->BootstrapGlyphicon->add()->Content->create('My Glorious Body');
 */
class BootstrapJumbotronEntity extends BootstrapHelperEntity{
	public $Title = null;
	public $Body = null;
	public $_options = [
		'tag' => 'div',
		'class' => 'jumbotron container',
	];

	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Body = new BootstrapJumbotronBodyEntity($view, $settings);
        $this->Title = new BootstrapJumbotronTitleEntity($view, $settings);
    }

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data) && is_string($options)){
			$this->Title->create($data, [], $keyRemaps, $valueRemaps);
			$this->Body->create($options, [], $keyRemaps, $valueRemaps);
			$options = [];
		}
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

	public function __toString(){
		$result = [];
		$result[] = $this->Title->toString();
		$result[] = $this->Body->toString();

		$options = $this->options();
    	$options[$this->_contentToken] = implode(PHP_EOL, $result);

    	$this->options($options);

    	return parent::__toString();
	}

}

/**
 * Holds title info and wraps it in a tag
 * @package default
 */
class BootstrapJumbotronTitleEntity extends BootstrapHelperEntity{
	public $_options = [
		'tag' => 'h1',
	];
}
/**
 * Holds body info and wraps it in a tag
 * @package default
 */
class BootstrapJumbotronBodyEntity extends BootstrapHelperEntity{
	public $_options = [
		'tag' => 'p',
	];
}

?>