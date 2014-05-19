<?php

App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');

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