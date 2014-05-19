<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
// App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');


class BootstrapModalTriggerEntity extends BootstrapHelperEntity{
	// public $remoteURL = null;
	public $helpers = ['JS','Bootstrap.BootstrapHtml','Session'];
	public $_options = [
		'tag'=>'button',
		'baseClass'=> 'btn btn-:type btn-:size btn-:context',
		'type'=>'default',
		'size'=>'md', #sm, md, lg
		'context'=>'default',
		'htmlAttributes'=>[
			'data-toggle'=>'modal',
			'data-target'=>'#:targetId',
			'id'=>':id'
		],
		'content'=>':content',
		'ajax'=>[
			'event'=>'click',
			//'title'=>':content',
			'url'=>':url',
			'options'=>[
				'update'=>'#:targetId-body',
				'buffer'=>false,
			],
		]
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($options)):
			$this->setUnless($data, 'targetId', $data);
			$this->setUnless($data,'content', $options);
			$options = [];
		else:
			$this->setDefault($data,'content',$data);		
		endif;	

		if(!isset($options['id'])):
			$options['id'] = ':targetId-trigger-'.$this->generateId();
		endif;
		$this->setUnless($data,'targetId',$this->getParentNode()->id);

		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}

	public function __toString(){
		$options = $this->options;
		$result = [];
		$result[] = $this->safeInsertData("<:tag :htmlAttributes>:content</:tag>",$options);
		if($this->isDirty($options, 'ajax.url')):
			$result[] = "<script type='text/javascript'>";
			$result[] = "\$('#{$this->id}').bind('click', function (event) { \$.ajax({dataType:'html', success:function (data, textStatus) {\$('{$options['ajax']['options']['update']}').html(data);}, url:'{$options['ajax']['url']}'}); }); ";
			$result[] = "</script>";
		endif;

		return implode(PHP_EOL, $result);
	}

	public function getTargetId(){ return $this->options()['ajax']['options']['update'];}
}

class BootstrapModalWindowEntity extends BootstrapHelperEntity{
	public $remoteURL = null;
	/**
	 * suppressWrapper lets you disable the full modal wrap for cases where you want to replace content of existing modals using an ajax call for example
	 * @var boolean true will cause toString to NOT print out the root modal div
	 */ 
	public $suppressWrapper = false;
	public $helpers = ['JS','Bootstrap.BootstrapHtml','Session'];
	public $_options = [
		'tag'=>'div',
		'baseClass'=> 'modal-dialog modal-:size',
		'size'=>'md', #sm, md, lg
		'wrapper'=>[
			'tag'=>'div',
			'baseClass'=> 'modal fade',
			'htmlAttributes'=>[
				'id'=>':id',
				'tabindex'=>-1,
				'role'=>'dialog',
				'aria-hidden'=>true,
			],
		],
		'content'=>[
			'tag'=>'div',
			'baseClass'=>'modal-content',
		],
		'header'=>[
			'tag'=>'div',
			'baseClass'=>'modal-header',
			'content'=> ':header',
			'close'=>[
				'tag'=>'button',
				'type'=>'button',
				'baseClass'=>'close',
				'content'=>'x',
				'htmlAttributes'=>[
					'data-dismiss'=>'modal',
					'aria-hidden'=>true,
				],
				
			],
			'title'=>[
				'tag'=>'h4',
				'baseClass'=>'modal-title',
				'content'=>':title'
			]
		],
		'body'=>[
			'tag'=>'div',
			'baseClass' => 'modal-body',
			'content' => ':body',
			'htmlAttributes'=>[
				'id'=>':id-body'
			],
		],
		'after' => ':after',
		'footer'=>[
			'tag'=>'div',
			'baseClass' => 'modal-footer',
			'content' => ':footer',
			'close'=>[
				'tag'=>'button',
				'content'=>'Close',
				'baseClass'=>'btn btn-default',
				'htmlAttributes'=>[
					'data-dismiss'=>'modal',
					'type'=>'button',

				],
				
			],
		],
	];

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
			
		if(is_string($options)):
			$this->setUnless($data,'title',$data);
			$this->setUnless($data,'body', $options);
			$options = [];
		else:
			$this->setDefault($data,'body',$data);
		endif;	

		$this->setUnless($data,'id',$this->getParentNode()->id);

		return parent::create($data, $options, $keyRemaps, $valueRemaps);

	}

	public function __toString(){
		$result = [];
		$options = $this->options;

		if(!is_null($this->id) && !$this->suppressWrapper):
		$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options['wrapper']);
		endif;
			$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options);
				$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options['content']);
					#if header (and/or title)
					$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options['header']);
						$result[] = $this->safeInsertData("<:tag :htmlAttributes >:content</:tag>", $options['header']['close']);
						if($this->isDirty($options,'header.title.content')):
							$result[] = $this->safeInsertData("<:tag :htmlAttributes>:content</:tag>", $options['header']['title']);
						else:
							$result[] = $this->safeInsertData(":content", $options['header']);
						endif;
					$result[] = $this->safeInsertData("</:tag><!--end modal header-->", $options['header']);
				
					#if body
					$options['body']['content'] = $this->Session->flash().$options['body']['content'];
					if(!is_null($this->remoteURL)):
						$options['body']['content'] .= "<script type='text/javascript'>";
						$options['body']['content'] .= "\$('#{$this->id}').on('show.bs.modal', function (event) {\$.ajax({dataType:'html', success:function (data, textStatus) {\$('{$options['body']['htmlAttributes']['id']}').html(data);}, url:'{$this->remoteURL}'}); }); ";
						$options['body']['content'] .= "</script>";
					endif;
					if ($this->isDirty($options, 'body.content')):
						$result[] = $this->safeInsertData("<:tag :htmlAttributes>:content</:tag><!--end modal body-->", $options['body']);
					endif;
					
					#if table, list, etc
					if ($this->isDirty($options, 'after')):
						$result[] = $options['after'];
					endif;

					#if footer		
					$result[] = $this->safeInsertData("<:tag :htmlAttributes>", $options['footer']);
						$result[] = $this->safeInsertData("<:tag :htmlAttributes>:content</:tag>", $options['footer']['close']);
						if ($this->isDirty($options, 'footer.content')):
							$result[] = $this->safeInsertData(":content", $options['footer']);
						endif;
					$result[] = $this->safeInsertData("</:tag><!--end modal footer-->", $options['footer']);
					

				$result[] = $this->safeInsertData("</:tag><!--end modal content -->", $options['content']);
			$result[] = $this->safeInsertData("</:tag><!--end modal dialog -->", $options);
		if(!is_null($this->id)):
		$result[] = $this->safeInsertData("</:tag><!--end modal-->", $options['wrapper']);
		endif;

		return implode(PHP_EOL, $result);

	}

}

class BootstrapModalEntity extends BootstrapHelperEntity{

	public $Trigger = null;
	public $Window = null;

	public function __toString(){
		$result = [];
		$result[] = $this->Trigger->toString();
		$result[] = $this->Window->toString();
		return implode(PHP_EOL, $result);
	}
	
	public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->Window = new BootstrapModalWindowEntity($view, $settings);
        $this->Trigger = new BootstrapModalTriggerEntity($view, $settings);

        $this->Window->setParentNode($this);
        $this->Trigger->setParentNode($this);
    }
		
}

class BootstrapModalHelper extends BootstrapHelperEntityCollection{
	public $_entityClass = 'BootstrapModalEntity';
}
?>