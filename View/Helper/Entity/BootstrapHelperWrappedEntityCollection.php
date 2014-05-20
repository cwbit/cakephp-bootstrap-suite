<?php

App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');

/**
 * Extension of BootstrapHelperEntityCollection that provides the ability
 * to wrap the child $entities automatically
 * 
 * For example, imagine you had $entities that were all <li>, you would want
 * the whole collection to be wrapped in a <ul>. This class will do just that
 * 
 * Another, actually used example, would be the case where you have a number of
 * Table body rows and you need to wrap them all in a <tbody> when the row collection prints out
 * 
 * @package default
 */
class BootstrapHelperWrappedEntityCollection extends BootstrapHelperEntityCollection{
	
	public function __toString(){
		# if the collection hasn't been officially created, return empty
		if(!$this->_wasCreated):
			return '';
		endif;

		$options = $this->options();

		$options[$this->_contentToken] = parent::__toString();

		return $this->safeInsertData($this->_pattern, $options);
	}

}
?>