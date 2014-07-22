<?php

App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');

/**
 * BootstrapHelperEntityCollection is, as the name suggests, a class designed specifically
 * to handle a collection of BootstrapHelperEntity(ies)
 * 
 * It uses public $entities to store a collection (array) of entities of technically any type
 * By default it will create child entities using the class specified in $this->_entityClass
 * 
 * In its current form, the Collection also implements ArrayAccess although this might be dropped in the future
 * as it is less explicit
 * 
 * add() and addMultiple() are the intended methods for adding new instances of $_entityClass to $entities
 * 
 * By default an EntityCollection::__toString() call will only return the (string) value of its $this->entities
 * If you want the collection to wrap it's entities in something (for example ul class that creates li entities
 * and needs to wrap the li collection in a ul) use the BootstrapHelperWrappedEntityCollection
 * @package default
 */
class BootstrapHelperEntityCollection extends BootstrapHelperEntity implements ArrayAccess{
		
	/**
	 * Class the collection will use for all of its $entities
	 * @var string
	 */
	protected $_entityClass = 'BootstrapHelperEntity';
	/**
	 * Array of all child entities
	 * @var array
	 */
	public $entities = [];

	/**
	 * Returns the (string) representation of all its children iif it ($this) $this->_wasCreated
	 * 
	 * If you need to also call the BootstrapHelperEntity::__toString, then use a BootstrapHelperWrappedEntityCollection instead
	 * @return type
	 */
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

	/**
	 * This passthrough function will allow create() calls on an EntityCollection to generate a one-off instance of $_entityClass to be called; instead of always first requiring an add() call (which stores each child $_entityClass instance in the parent helper - which can be very memory intensive and is generally not needed)
	 * 
	 * @see BootsrapHelperEntity::create()
	 * @return BootstrapHelperEntity
	 */
	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		$p = new $this->_entityClass($this->_view, $this->_settings);
		return $p->create($data, $options, $keyRemaps, $valueRemaps);
	}

	/**
	 * Calls add() foreach $entities as $entity where $entity is an array of params for the create() function
	 * @param array $entities an array of arrays
	 * @return BootstrapHelperEntityCollection returns $this
	 */
	public function addMultiple($entities){
		if(func_num_args()>1):
			$entities = func_get_args();
		endif;
		foreach($entities as $entity):
			call_user_func_array([$this,'add'], $entity);
		endforeach;

		return $this;
	}

	/**
	 * Wrapper for BootstrapEntityHelper::create() that handles Collection-specific logic including the
	 * storage of $this as the parent node of each of its child entities (tree based)
	 * 
	 * @see BootstrapEntityHelper::create()
	 * @param string $data 
	 * @param mixed $options 
	 * @param mixed $keyRemaps 
	 * @param mixed $valueRemaps 
	 * @return BootstrapHelperEntity (or subclass)
	 */
	public function add($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		#if this is the first time we've called add() on the collection, we need to create() it
		if(!$this->_wasCreated):
			$this->create();
		endif;

		// $p = new $this->_entityClass($this->_view, $this->_settings);
		// $p->create($data, $options, $keyRemaps, $valueRemaps);
		$p = $this->create($data, $options, $keyRemaps, $valueRemaps);
		$this->entities[$p->id] = $p;
		$p->setParentNode($this);
		return $p;
	}

	/**
	 * Gets either the child entity with id = $id or the complete set of child entities
	 * @param string $id 
	 * @return mixed
	 */
	public function get($id = null){
		if(!is_null($id)):
			return (isset($this->entities[$id])) ? $this->entities[$id] : null;
		endif;
		return $this->entities;
	}	
	
	/**
	 * Setter method allows you to explicity set a child entity
	 * @param string $id 
	 * @param BootstrapHelperEntity $entity 
	 * @return void
	 */
	public function set($id, BootstrapHelperEntity $entity){
		return $this->entities[$id] = $entity;
	}

	/**
	 * Allows you to delete (unset) a specific entity or all $this->entities if $id = null
	 * @param type $id 
	 * @return type
	 */
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