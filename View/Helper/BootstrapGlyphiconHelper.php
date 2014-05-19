<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

// $newGlyph = $this->BootstrapGlyphicon->add();
class BootstrapGlyphiconHelper extends BootstrapHelperEntityCollection{

	protected $_entityClass = 'BootstrapGlyphiconEntity';

}


// echo $this->BootstrapGlyphicon->add('arraow-up');
class BootstrapGlyphiconEntity extends BootstrapHelperEntity{
	protected $_options = [
		'tag'=>'span',
		'baseClass'=>'glyphicon glyphicon-:name',
		'content'=>'', #glyphicons have no content - only classes
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data)){
			$this->setUnless($data, 'name', $data);
		}
		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

}



// App::uses('BootstrapHelper','Bootstrap.View/Helper');

// class BootstrapGlyphiconHelper extends BootstrapHelper{

// 	protected $_options = [
// 		'create'=>[
// 			'tag'=>'span',
// 			'baseClass'=>'glyphicon glyphicon-:name',
// 		]
// 	];

// 	*
// 	 * Takes a (short format) glyph name from http://getbootstrap.com/components/#glyphicons-glyphs
// 	 * and turns it into a valid glyphicon
// 	 * 
// 	 * e.g. create('volume-down') will return
// 	 * "<span class='glyphicon glyphicon-volume-down'></span>";
// 	 * 
// 	 * @param string $glyph the short name of the glyphicon - e.g. 'volume-down'
// 	 * @link http://getbootstrap.com/components/#glyphicons-glyphs
// 	 * @return string the glyphicon HTML string
	 
// 	public function create($glyph, $options =[]){
// 		$this->setDefault($glyph, 'name', $glyph);
// 		$this->mergeOptions('create',$options);
// 		$this->insertData($options,$glyph);
// 		return $this->safeInsertData("<:tag class=':class' :htmlAttributes></:tag>",$options);
// 	}
// }