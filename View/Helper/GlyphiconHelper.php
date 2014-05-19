<?php

trigger_error("Bootstrap.GlyphiconHelper is deprectated, use Bootstrap.BootstrapGlyphiconHelper");

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class GlyphiconHelper extends BootstrapHelper{

	/**
	 * Takes a (short format) glyph name from http://getbootstrap.com/components/#glyphicons-glyphs
	 * and turns it into a valid glyphicon
	 * 
	 * e.g. glyphicon('volume-down') will return
	 * "<span class='glyphicon glyphicon-volume-down'></span>";
	 * 
	 * @param string $glyph the short name of the glyphicon - e.g. 'volume-down'
	 * @link http://getbootstrap.com/components/#glyphicons-glyphs
	 * @return string the glyphicon HTML string
	 */
	public function glyphicon($glyph){
		return "<span class='glyphicon glyphicon-$glyph'></span>";
	}
}