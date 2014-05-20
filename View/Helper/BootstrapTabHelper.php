<?php

// App::uses('BootstrapHelper','Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapTabHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapTabSetEntity';
}

class BootstrapTabSetEntity extends BootstrapHelperEntityCollection{
	public $Tabs = null;
	public $Panes = null;
	
	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Tabs = new BootstrapTabCollection($view, $settings);
        $this->Panes = new BootstrapPaneCollection($view, $settings);
    }

    public function activate($id = null){
    	$id = (!is_null($id)) ? $id : array_keys($this->Tabs->get())[0];
    	$this->Tabs[$id]->isActive(true);
    	$this->Panes[$id]->isActive(true);
    }

    public function addSet($id, $tab, $pane){
    	$this->Tabs->add($id, $tab);
    	$this->Panes->add($id, $pane);
    	return $this;
    }

	public function __toString(){
		$result = [];
		
		$result[] = $this->Tabs->toString();
		$result[] = $this->Panes->toString();

		return implode(PHP_EOL, $result);
	}	

}

class BootstrapPaneCollection extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapPaneEntity';
	// protected $_pattern = "<:tag :htmlAttributes>:content</:tag>";
	public $_options = [
		'tag'		=> 'div',
		'baseClass'	=> 'tab-content',
	];	
}
class BootstrapTabCollection extends BootstrapHelperWrappedEntityCollection{
	public $_entityClass = 'BootstrapTabEntity';
	// protected $_pattern = "<:tag :htmlAttributes>:content</:tag>";
	public $_options = [
		'tag'		=> 'ul',
		'baseClass'	=> 'nav nav-:type',
		'type'		=> 'tabs'

	];
	
}

class BootstrapPaneEntity extends BootstrapHelperEntity{

	public $active = false;
	public $_options = [
			'tag'		=> 'div',
			'baseClass'	=> 'tab-pane',
			'content'	=> ':content',
			'htmlAttributes'=>[
				'id'	=> ':id',
				],
		];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data) && is_string($options)):
			$this->setUnless($data,'id',$data);
			$this->setUnless($data,'content',$options);
			$options = [];
		endif;
		$this->setDefault($data,'content', $data);
		$this->setUnless($data, 'id', $this->generateId());

		return parent::create($data, $options, $keyRemaps, $valueRemaps);

	}

	public function __toString(){
		$result = [];
		$options = $this->options();
		if($this->isActive()):
			$options['baseClass'] .= ' active';
		endif;

		$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options);
		$result[] = $options['content'];
		$result[] = $this->safeInsertData("</:tag>", $options);

		return implode(PHP_EOL, $result);
	}

	public function isActive($active = null){
		if(!is_null($active)):
			$this->active = $active;
		endif;
		return $this->active;
	}
}

class BootstrapTabEntity extends BootstrapHelperEntity{
	public $id = '';
	public $active = false;
	public $_options = [
			'tag'		=> 'li',
			'baseClass'	=> '',
			'content'	=> ':content',
			'a' 		=> [
				'tag'	=> 'a',
				'htmlAttributes' => [
					'data-toggle' => 'tab',
					'href'	=> '#:id',
				],
			],
		];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data) && is_string($options)):
			$this->setUnless($data,'id',$data);
			$this->setUnless($data,'content',$options);
			$options = [];
		endif;
		$this->setDefault($data,'content', $data);
		$this->setUnless($data, 'id', $this->generateId());
		
		return parent::create($data, $options, $keyRemaps, $valueRemaps);

	}

	public function __toString(){
		$result = [];
		$options = $this->options();
		if($this->isActive()):
			$options['baseClass'] .=' active';
		endif;
		$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options);
		$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options['a']);
		$result[] = $options['content'];
		$result[] = $this->safeInsertData("</:tag>", $options['a']);
		$result[] = $this->safeInsertData("</:tag>", $options);

		return implode(PHP_EOL, $result);
	}

	public function isActive($active = null){
		if(!is_null($active)):
			$this->active = $active;
		endif;
		return $this->active;
	}

}


?>