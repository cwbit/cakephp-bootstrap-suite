<?php

App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapHelperWrappedEntityCollection extends BootstrapHelperEntityCollection{
	
	public function __toString(){
		$options = $this->options();

		$options[$this->_contentToken] = parent::__toString();

		return $this->safeInsertData($this->_pattern, $options);
	}
}
?>