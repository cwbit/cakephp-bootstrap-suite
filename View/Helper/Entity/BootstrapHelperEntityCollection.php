<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');

class BootstrapHelperEntityCollection extends BootstrapHelperEntity implements ArrayAccess{
		
	protected $_entityClass = 'BootstrapHelperEntity';
	public $entities = [];

	public function __toString(){
		# if this entity collection hasn't been officially created, then it should be treated as if it's EMPTY
		if(!$this->_wasCreated):
			return '';
		endif;

		$result = [];
		foreach($this->entities as $entityId => $entity):
			$result[] = $entity->toString();
		endforeach;	

		return implode(PHP_EOL, $result);
	}

	public function addMultiple($entities){
		if(func_num_args()>1):
			$entities = func_get_args();
		endif;
		foreach($entities as $entity):
			call_user_func_array([$this,'add'], $entity);
		endforeach;

		return $this;
	}

	public function add($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		#if this is the first time we've called add() on the collection, we need to create() it
		if(!$this->_wasCreated):
			$this->create();
		endif;

		$p = new $this->_entityClass($this->_view, $this->_settings);
		$p->create($data, $options, $keyRemaps, $valueRemaps);
		$this->entities[$p->id] = $p;
		$p->setParentNode($this);
		return $p;
	}

	public function get($id = null){
		if(!is_null($id)):
			return (isset($this->entities[$id])) ? $this->entities[$id] : null;
		endif;
		return $this->entities;
	}	
	
	public function set($id, BootstrapHelperEntity $entity){
		return $this->entities[$id] = $entity;
	}

	public function delete($id = null){
		if(!is_null($id)):
			if(isset($this->entities[$id])):
				unset($this->entities[$id]);
			endif;
		else:
			$this->entities = [];
		endif;
	}

	/**
	 * ArrayAccess functions
	 */
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->entities[] = $value;
		} else {
			$this->entities[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->entities[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->entities[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->entities[$offset]) ? $this->entities[$offset] : $this->entities;
	}

}
?>