<?php

// App::uses('BoostCakeFormHelper','Bootstrap.View/Helper');
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

class BootstrapFormHelper extends BootstrapHelper {// extends BoostCakeFormHelper{

	public $helpers = ['Bootstrap.BootstrapHtml','Bootstrap.BoostCakeForm'];

	public $_args = [
			'create'=>[  #Form->create
				1=>[	#second argument (options) defaults
					'inputDefaults' => [
						'div' => 'form-group',
						'wrapInput' => false,
						'class' => 'form-control'
					],
					'class' => 'well'
				],
			],
			'submit'=>[
				1=>[
					'div' => 'form-group',
					'class' => 'btn btn-default'
				]
			]
		];

	protected $_options = [
			'helpBlock'=>[
				'tag' => 'span',
				'baseClass' =>'help-block',
				'class'=>'',
				'content' => ':description',
			],
			'formGroup'=>[
				'tag' => 'div',
				'baseClass' =>'form-group',
				'class'=>':class',
				'content' => ':content',
			],
			'staticInput'=>[
				'tag' => 'p',
				'baseClass' =>'form-control-static',
				'class'=>'',
				'content' => ':content',
			],
			'radio'=>[
				'label'=>[
					'tag'=>'label',
					'baseClass'=>'',
				],
				'wrapper'=>[
					'tag'=>'div',
					'baseClass'=>'radio',
				],
				'input'=>[
					'tag'=>'input',
					'htmlAttributes'=>[
						'type'=>'radio',
					]
				],
				'hidden'=>[
					'tag'=>'input',
					'htmlAttributes'=>[
						'type'=>'hidden',
						'value'=>'',
					]

				]
			]

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
		
		if(method_exists($this->BoostCakeForm, $method)):
			return call_user_func_array([$this->BoostCakeForm,$method], $args);
		endif;
	}

	public function helpBlock($data, $options = []){
		if(is_string($data)):
			$this->setUnless($data,'description',$data);
		endif;
		$this->mergeOptions('helpBlock',$options);
		$this->insertData($options, $data);

		return $this->BootstrapHtml->tag($options['tag'], $options['content'], ['class'=>$options['baseClass'].$options['class']]);
	}

	public function formGroupBegin($data ='', $options = []){
		$this->setDefault($data,'class',$data);
		$this->mergeOptions('formGroup',$options);
		$this->insertData($options, $data);

		return String::insert("<:tag class=':baseClass :class'>",$options);

	}
	public function formGroupEnd($data ='', $options = []){
		$this->mergeOptions('formGroup',$options);
		$this->insertData($options, $data);

		return String::insert("</:tag>",$options);

	}

	public function radio($name, $list, $options = []){
		$attributes = $this->BoostCakeForm->_initInputField($name, []); #required to appl security to the field and get generated field name, side note about using _initInputField. Technically Helper.php has this function so it needs to be explicity called form the BoostCakeFormHelper otherwise the 'name' doesn't get filled right and the report will blackhole

		$value = $this->BoostCakeForm->value($name);
		$this->mergeOptions('radio',$options);
		
		$name = $attributes['name'];
		$inputId = $attributes['id'];

		$result = [];
		$result[] = $this->safeInsertData("<:tag id='{$inputId}_' name='$name' :htmlAttributes />",$options['hidden']);			
		$inline = isset($options['inline']);

		if (!$inline):
			$result[]=$this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options['wrapper']);
		else:
			$this->setUnless($options['label'],'class','');
			$options['label']['class'] .= ' radio-inline';
		endif;
		foreach($list as $id => $title):
			$inputId = $attributes['id'].$id;
			if($id == $value):
				$options['input']['htmlAttributes']['checked']='checked';
			endif;
			$result[]=$this->safeInsertData("<:tag id='$inputId' value='$id' name='$name' :htmlAttributes />",$options['input']);			
			$result[]=$this->safeInsertData("<:tag for='$inputId' class=':class' :htmlAttributes >",$options['label']);
			$result[]=$title;
			$result[]=$this->safeInsertData("</:tag>",$options['label']);

			unset($options['input']['htmlAttributes']['checked']);
		endforeach;
		if (!$inline):
			$result[]=$this->safeInsertData("</:tag>",$options['wrapper']);
		endif;

		return implode(PHP_EOL, $result);

	}
	
	// public function staticInput($data, $options = []){
	// 	$this->setUnless($data,'content',$data);
	// 	$this->mergeOptions('helpBlock',$options);
	// 	$this->insertData($options, $data);

	// 	return $this->BootstrapHtml->tag($options['tag'], $options['content'], ['class'=>$options['baseClass'].$options['class']]);

	// }

}

?>