<?php

App::uses('BoostCakeHtmlHelper','Bootstrap.View/Helper');
App::uses('BootstrapHelper','Bootstrap.View/Helper');
/**
 * This is an extremely special needs helper. 
 * We have to override FormHelper to support the formatting demands of Bootstrap; BoostCakeFormHelper is designed for exactly 
 * this purpose.
 * But by itself BoostCakeFormHelper actually ONLY expands FormHelper to support BS 3.x. It doesn't actually provide
 * and default values, which is really what we're after.
 * 
 * To accomplish this we create an extension of our main BootstrapHelper that uses PHPs __call to look in
 * BoostCakeFormHelper for functions it doesn't have (namely, all of the functions in FormHelper).
 * Then, if BCFH has the function (e.g. create()), we combine any passed $args with default $arg values in $_args
 * 
 * The net result is that BCFH provides expanded FormHelper support for Bootstrap, and BootstrapFormHelper provides
 * default formatting parameters so we don't need to remember them each time.
 * 
 * Treat BootstrapFormHelper exactly like FormHelper or BCFH
 * e.g. BootstrapForm->input(...)
 */

class BootstrapHtmlHelper extends BootstrapHelper {// extends BoostCakeFormHelper{

	// public $uses = ['Bootstrap'];

	public $_args = [
			'foo'=>[  #$this->Html->foo
				0=>[	#second argument (options) defaults
					'bar'
				],
			],
		];

	protected $_options = [
			'table'=>[
				'tag' => 'table',
				'baseClass' =>'table table-:hover table-:striped table-:bordered table-:condensed',
				'class'=>'',
				'striped' =>'striped',
				'bordered' =>'notbordered', #set to 'bordered' to use
				'hover' =>'hover',
				'condensed' =>'notcondensed', #set to 'condensed' to use
				'content' => ':content',
			],
		];

	/**
	 * This function is called by PHP when a function is called on SELF that doesn't exist in SELF, or PARENT
	 * We are using this function in conjunction with BoostCakeFormHelper to provide an injection override
	 * for FormHelper.
	 * 
	 * Use BootstrapForm just like the FormHelper. If the method doesn't exist in SELF/PARENT (e.g. create()) then
	 * __call will be used to look in the BoostCakeFormHelper (whose PARENT is FormHelper and this create() exists)
	 * 
	 * Use the $_args array to inject default function options into function call
	 * 	e.g.
	 * 		$_args = [
	 * 				'end'=>[
	 * 					0 => 'GO BABY GO!'
	 * 				]
	 * 			]
	 * will inject the string 'GO BABY GO!' into the first parameter of FormHelper->end('GO BABY GO!')
	 * 
	 * For function declarations to inject/override, look here --> http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html
	 * 
	 * @param type $method 
	 * @param type $args 
	 * @return type
	 */
	public function __call($method, $args){
		# inject default $_args into the $args parameters (see var itself for details
		if( isset($this->_args[$method]) ):
			$args = Hash::merge($this->_args[$method], $args);
			ksort($args);
		endif;
		
		if(method_exists('BoostCakeHtmlHelper', $method)):
			$c = $this->_View->loadHelper('Bootstrap.BoostCakeHtml');
			return call_user_func_array([$c,$method], $args);
		endif;
	}

	public function tableBegin($data = [], $options = []){
		$this->mergeOptions('table',$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		return String::insert("<:tag class=':baseClass :class'>",$options);
	}

	public function tableEnd($data = [], $options = []){
		$this->mergeOptions('table',$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		return String::insert("</:tag>",$options);
	}

	public function dataTable($records, $controller, $fields = ['name'], $actions = ['view']){
		$this->Button = $this->_View->loadHelper('Bootstrap.Button');

		#build headers based on $fields and if action
		foreach($fields as $key => $field):
			if(is_string($key)):
				$headers[] = Inflector::humanize($key);
			elseif(is_string($field)):
				$headers[] = Inflector::humanize(str_replace('.', '_', $field));
			endif;
		endforeach;
		if(!empty($actions)):
			$headers[] = "Options";
		endif;

		#build cells based on $fields and action buttons
		$temp = '';
		foreach($actions as $key => $action):
			if(is_string($action)):
				$this->setUnless($action, 'action',$action);
			endif;
			$this->setUnless($action,'action',Inflector::variable($key));
			$this->setUnless($action,'','id');
			$this->setUnless($action,'controller',$controller);
			
			$key = (is_string($key)) ? $key : Inflector::humanize($action['action']);
			$temp .= $this->Button->button($key, $this->url($action));
		endforeach;
		$actions = $this->Button->group($temp);
		unset($temp);
		$rows = [];
		foreach($records as $key => $record):
			$row = [];
			foreach($fields as $field):
				$field = (array)$field;
				$temp = [];
				foreach($field as $key => $value):
					$temp[] = Hash::get($record,$value);
				endforeach;
				$row[] = implode($temp,' '); 
				unset($temp);
			endforeach;
			$row[] = $actions;
			$rowData = (!isset($record[Inflector::humanize(Inflector::singularize($controller))])) ? $record : $record[Inflector::humanize(Inflector::singularize($controller))];

				$this->insertData($row, $rowData); #only supports replacement of main model data right now
			$rows[] = $row;
		endforeach;

		$result = $this->tableBegin();
			$result .= "<thead>";
				$result .= $this->tableHeaders($headers);
			$result .= "</thead>";
			$result .= "<tbody>";
				$result .= $this->tableCells($rows);
			$result .= "</tbody>";
		$result .= $this->tableEnd();

		return $result;


	}

}

?>