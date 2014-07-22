<?php
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperMultipartEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
// App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapThumbnailHelper extends BootstrapHelperEntityCollection{
	protected $_entityClass = 'BootstrapThumbnailEntity';
}

class BootstrapThumbnailEntity extends BootstrapHelperMultipartEntity{

	protected $parts = [
		'Image' => 'BootstrapThumbnailImageEntity',
		'Caption' => 'BootstrapThumbnailCaptionEntity'
		];

	protected $_options = [
		'tag' => 'div',
		'baseClass' => 'thumbnail',
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data)):
			if(is_string($options)):
				if(is_string($keyRemaps)):
					$this->Caption->Title->create($keyRemaps);
					$keyRemaps = false;
				endif;
				$this->Caption->create($options, [], $keyRemaps, $valueRemaps);
				$options = [];
			endif;
			$this->Image->create($data, $options, $keyRemaps, $valueRemaps);
			$data = '';
		endif;
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

	// public $Image = null;
	// public $Caption = null;

	// public function __construct(View $view, $settings = array()) {
 //        parent::__construct($view, $settings);
 //        $this->Image = new BootstrapThumbnailImageEntity($view, $settings);
 //        $this->Caption = new BootstrapThumbnailCaptionEntity($view, $settings);
 //    }

 //    public function __toString(){
 //    	$result = [];
 //    	$result[] = (string) $this->Image;
 //    	$result[] = (string) $this->Caption;

 //    	$options = $this->options();
 //    	$options[$this->_contentToken] = implode(PHP_EOL, $result);

 //    	$this->options($options);

 //    	return parent::__toString();
 //    }


}

class BootstrapThumbnailImageEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag' => 'img',
		'htmlAttributes' => [
			'src' => ':url',
			'class' => 'img-responsive',
			'alt' => '',
		],
	];

	protected $_pattern = '<:tag :htmlAttributes />';

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		$this->setDefault($data, 'url', $data);
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}
}

class BootstrapThumbnailCaptionEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag' => 'div',
		'baseClass' => 'caption',
	];

	public $Title = null;

	public function __construct(View $view, $settings = []){
		parent::__construct($view, $settings);

		#use 'disposable' entity for Title
		$title = ['tag'=>'h1']; #b/c it has nothing special
		$this->Title = new BootstrapHelperEntity($view, $settings+$title); 
	}

    public function __toString(){
    	$result = [];
    	$result[] = $this->Title->toString();

    	$data = $this->data();
    	$this->setUnless($data, $this->_contentToken, ':content');
    	$data[$this->_contentToken] = implode(PHP_EOL, $result) . PHP_EOL . $data[$this->_contentToken];
    	$this->data($data);

    	return parent::__toString();
    }

}
?>