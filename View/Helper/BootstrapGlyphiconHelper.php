<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class BootstrapGlyphiconHelper extends BootstrapHelper{

	protected $_options = [
		'create'=>[
			'tag'=>'span',
			'baseClass'=>'glyphicon glyphicon-:name',
		]
	];

	/**
	 * Takes a (short format) glyph name from http://getbootstrap.com/components/#glyphicons-glyphs
	 * and turns it into a valid glyphicon
	 * 
	 * e.g. create('volume-down') will return
	 * "<span class='glyphicon glyphicon-volume-down'></span>";
	 * 
	 * @param string $glyph the short name of the glyphicon - e.g. 'volume-down'
	 * @link http://getbootstrap.com/components/#glyphicons-glyphs
	 * @return string the glyphicon HTML string
	 */
	public function create($glyph, $options =[]){
		$this->setDefault($glyph, 'name', $glyph);
		$this->mergeOptions('create',$options);
		$this->insertData($options,$glyph);
		return $this->safeInsertData("<:tag class=':class' :htmlAttributes></:tag>",$options);
	}
}