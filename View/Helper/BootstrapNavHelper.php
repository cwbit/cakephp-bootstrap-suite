<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

class BootstrapNavHelper extends BootstrapHelper{

	public $helpers = ['Bootstrap.BootstrapHtml','Bootstrap.Button'];

	public $_options = [
			'nav'=>[
				'tag'=>'nav',
				'baseClass'=>'navbar navbar-:context navbar-fixed-:affix',
				'class'=>'',
				'context'=>'default', //or inverted
				'affix'=>'none', //options are ['bottom','top']
				'htmlAttributes'=>[
					'role'=>'navigation',
				],
				'container'=>[
					'tag'=>'div',
					'baseClass'=>'container-fluid',
					'class'=>'',
				]
			],
			'link'=>[
				'baseClass' => 'navbar-link',
			],
			'text'=>[
				'tag'=>'p',
				'baseClass'=>' navbar-text ',
				'class'=>'',
				'htmlAttributes'=>[],
				'content'=>':content',
			],
			'button'=>[
				'baseClass'=>'navbar-btn',
			],
			'header'=>[
				#HEADER requires the nav bar spans but it also needs the ID of the CONTENT section
				'header'=>[
					'tag'=>'div',
					'baseClass'=>'navbar-header',
					'class'=>'',
				],
				'brand'=>[
					// 'tag'=>'a',
					'baseClass'=>'navbar-brand',
					'class'=>'',
					'content'=>':label',
					'url'=>['controller'=>'pages','action'=>'home']
				],
				'button'=>[
					'baseClass'=>'navbar-toggle',
					'class'=>'',
					'htmlAttributes'=>[
						'data-toggle'=>'collapse',
						'data-target'=>'#:target',
						'type'=>'button',

					],
					'label'=>"
				        <span class='sr-only'>Toggle Main Navigation</span>
				        <span class='icon-bar'></span>
				        <span class='icon-bar'></span>
				        <span class='icon-bar'></span>
					",
				]
			],
			'content'=>[
				'tag'=>'div',
				'baseClass'=>'collapse navbar-collapse',
				'id'=>':id',
				'class'=>'',
			],
			'dropdown'=>[
				'baseClass'=>'dropdown',
				'label'=>':label',
			],
			'navGroup'=>[
				'tag'=>'ul',
				'baseClass'=>'nav navbar-nav navbar-:align',
				'class'=>'',
				'align'=>'left',//left or right,
				'content'=>':content',
				'htmlAttributes'=>[]
			]



		];

	private $_collapseId = null;

	public function navBegin($data = [], $options = []){
		$this->mergeOptions('nav',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);
		
		$result = $this->safeInsertData("<:tag class=':baseClass :class' :htmlAttributes>",$options);
		$result .= $this->safeInsertData("<:tag class=':baseClass :class' :htmlAttributes>",$options['container']);

		return $result;
	}
	public function navEnd($data = [], $options = []){
		$this->mergeOptions('nav',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);
		
		$result = $this->safeInsertData("</:tag>",$options['container']);
		$result .= $this->safeInsertData("</:tag>",$options);

		return $result;
	}

	public function header($data, $options = []){
		$this->setDefault($data,'label',$data);
		#set up the data-target
		$this->setUnless($data,'target',$this->generateId());
		// $this->setUnless($data,'url',['controller']=)
		$this->_collapseId = $data['target'];
		#merge options
		$this->mergeOptions('header',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);
		
		$result = [];
		$result[] = $this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options['header']);//$this->setHtmlAttributes($options['header']));
		$result[] = $this->button($data,$options['button']);
		$this->mergeClasses($options['brand']);
	
		$result[] = $this->link($options['brand']['content'],$options['brand']['url'],$options['brand']);
		$result[] = $this->safeInsertData("</:tag>",$options['header']);

		return implode('', $result);

	}

	public function contentBegin($data=[], $options = []){
		$this->setUnless($data,'id',$this->_collapseId);
		$this->mergeOptions('content',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);

		return $this->safeInsertData("<:tag class=':class' id=':id' :htmlAttributes>",$options);
	}

	
	public function contentEnd($data=[], $options = []){
		$this->mergeOptions('content',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);

		return $this->safeInsertData("</:tag><!--navbar content-->",$options);
	}

	/**
	 * wraps text in nav-specific class(es)
	 * @param type $data 
	 * @param type $options 
	 * @return type
	 */
	public function text($data = [], $options = []){
		$this->setDefault($data, 'content', $data);
		$this->mergeOptions('text',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);
		
		return $this->safeInsertData("<:tag class=':baseClass :class' :htmlAttributes>:content</:tag>",$options);
	}

	/**
	 * Wrapper for Html->link that just injects nav specific class(es)
	 * @return type
	 */
	public function link(){
		$options = [];
		$this->mergeOptions('link',$options);
		$args = func_get_args();
		$this->setUnless($args,2,[]);
		$this->setUnless($args[2],'class','');
		$args[2]['class'] .= ' '.$options['baseClass'];

		return call_user_func_array([$this->BootstrapHtml,'link'], $args);

	}
	/**
	 * Wrapper for Button->button that just injects nav specific class(es)
	 * @return type
	 */
	public function button(){
		$options = [];
		$this->mergeOptions('button',$options);
		$args = func_get_args();

		#if second param is string, assume Router::url string
		if(isset($args[1]) && is_string($args[1])):
			$args[0] = (array) $args[0];
			$args[0]['link'] = $args[1];
			unset($args[1]);
		endif;

		#inject default navbar class for buttons into options array
		$this->setDefault($args,1,[]);
		if(!isset($args[1]['class'])):
			$args[1]['class'] = '';
		endif;
		$args[1]['class'] .= $options['baseClass'];
		return call_user_func_array([$this->Button,'button'], $args);

	}

	public function dropdown($data = [], $list = [], $options=[]){
		$this->setDefault($data,'label',$data);
		$this->mergeOptions('dropdown',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);

		$result = [];
		
		$result[] = $this->safeInsertData("<li class=':class' :htmlAttributes>",$options);
		$result[] = $this->safeInsertData("<a href='#' class='dropdown-toggle' data-toggle='dropdown'>:label<b class='caret'></b></a>",$options);
		$result[] = $this->safeInsertData("<ul class='dropdown-menu'>",$options);

		foreach($list as $item):
			if(is_null($item) or $item === 'divider'):
				$result[] = $this->safeInsertData("<li class='divider' ></li>", $options);
			else:
				$result[] = $this->safeInsertData("<li >$item</li>", $options);
			endif;
		endforeach;

		$result[] = $this->safeInsertData("</ul><!-- navbar dropdown list -->",$options);
		$result[] = $this->safeInsertData("</li><!-- navbar dropdown wrapper -->",$options);

		return implode(PHP_EOL, $result);

	}

	public function groupBegin($data = [], $options = []){
		$this->setDefault($data,'content',$data);
		$this->mergeOptions('navGroup',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);

		return $this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options);
	}

	public function groupEnd($data = [], $options = []){
		$this->setDefault($data,'content',$data);
		$this->mergeOptions('navGroup',$options);
		$this->insertData($options,$data);
		$this->insertData($options,$options);

		return $this->safeInsertData("</:tag><!-- navbar navGroup -->",$options);
	}


	public function group($data, $options = []){
		$this->setDefault($data,'content',$data);

		$result = [];
		$result[] = $this->groupBegin($data, $options);//$this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options);
		$result[] = $this->safeInsertData(":content",$data);
		$result[] = $this->groupEnd($data, $options);//$this->safeInsertData("</:tag><!-- navbar navGroup -->",$options);

		return implode(PHP_EOL,$result);

	}



}
?>