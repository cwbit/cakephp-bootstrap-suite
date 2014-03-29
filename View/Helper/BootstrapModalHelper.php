<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

class BootstrapModalHelper extends BootstrapHelper{

	public $helpers = ['JS','Bootstrap.BootstrapHtml','Session'];

	public $_options = [
			'trigger'=>[
				'tag'=>'button',
				'baseClass'=> 'btn btn-:type btn-:size',
				'class'=>'',
				'type'=>'default',
				'size'=>'md', #sm, md, lg
				'data-toggle'=>'modal',
				'data-target'=>':id',
				'content'=>':content',
				'id'=>'',
				'ajax'=>[
					'event'=>'mouseenter',
					'title'=>':content',
					'url'=>':url',
					'options'=>[
						'update'=>':id',
						'buffer'=>false,
					],
				]
			],
			'modal' =>[
				'tag'=>'div',
				'baseClass'=> 'modal-dialog modal-:size',
				'class'=>'',
				'size'=>'md', #sm, md, lg
				'wrapper'=>[
					'tag'=>'div',
					'baseClass'=> 'modal fade',
					'class'=>'',
					'id'=>':id',
					'tabindex'=>-1,
					'role'=>'dialog',
					'aria-hidden'=>true,
				],
				'content'=>[
					'tag'=>'div',
					'baseClass'=>'modal-content',
					'class' => '',
				],
				'header'=>[
					'tag'=>'div',
					'baseClass'=>'modal-header',
					'class' => '',
					'content'=> ':header',
					'close'=>[
						'tag'=>'button',
						'type'=>'button',
						'baseClass'=>'close',
						'class' => '',
						'data-dismiss'=>'modal',
						'aria-hidden'=>true,
						'content'=>'x',
					],
					'title'=>[
						'tag'=>'h4',
						'baseClass'=>'modal-title',
						'class'=>'',
						'content'=>':title'
					]
				],
				'body'=>[
					'tag'=>'div',
					'baseClass' => 'modal-body',
					'class'=> '',
					'content' => ':body',
				],
				'after' => ':after',
				'footer'=>[
					'tag'=>'div',
					'baseClass' => 'modal-footer',
					'class'=> '',
					'content' => ':footer',
					'close'=>[
						'tag'=>'button',
						'type'=>'button',
						'baseClass'=>'btn btn-default',
						'class' => '',
						'data-dismiss'=>'modal',
						'content'=>'Close',
					],
				],
			]
		];

	public function trigger($id, $data, $options = []){
		if (is_string($data)):
			$temp = [];
			$temp['content'] = $data;
			$data = $temp;
		endif;
		if(isset($data['url'])):
			$options['ajax']['url'] = $data['url']; // = Router::url($data['url'],['escape'=>true]);
		endif;
		
		$data['id']='#'.str_replace('#', '', $id);
		$this->mergeOptions('trigger',$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		if(!$this->isDirty($options,'id')):
			$options['id'] = 'trigger-'.intval(mt_rand());#random ID for the event
		endif;
		
		$result = String::insert("<:tag id=':id' data-toggle=':data-toggle' data-target=':data-target' class=':baseClass :class' >:content</:tag>",$options);
		if($this->isDirty($options,'ajax.url')):
			$requestString = $this->JS->request($options['ajax']['url'], $options['ajax']['options']);
			$script = $this->JS->get('#'.$options['id'])->event($options['ajax']['event'], $requestString, $options['ajax']['options']);
			$script = str_replace('bind', 'on', $script);
			$result .= $this->BootstrapHtml->scriptBlock($script);
			unset($script,$requestString);
		endif;

		return $result;
	}

	public function create($id = '', $data, $options = []){
		if (is_string($data)):
			$this->setUnless($data,'body',$data);		
		endif;
		$this->setUnless($data,'id',$id);	

		$this->mergeOptions('modal',$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		$result = '';

		if($id !== ''):
		$result .= String::insert("<:tag id=':id' tabindex=':tabindex' role=':role' aria-hidden=':aria-hidden' class=':baseClass :class'>", $options['wrapper']);
		endif;
			$result .= String::insert("<:tag class=':baseClass :class'>", $options);
				$result .= String::insert("<:tag class=':baseClass :class'>", $options['content']);
					#if header (and/or title)
					$result .= String::insert("<:tag class=':baseClass :class'>", $options['header']);
						$result .= String::insert("<:tag type=':type' data-dismiss=':data-dismiss' aria-hidden=':aria-hidden' class=':baseClass :class' >:content</:tag>", $options['header']['close']);
						if($this->isDirty($options,'header.title.content')):
							$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['header']['title']);
						else:
							$result .= String::insert(":content", $options['header']);
						endif;
					$result .= String::insert("</:tag>", $options['header']);
				
					#if body
					$options['body']['content'] = $this->Session->flash().$options['body']['content'];
					if ($this->isDirty($options, 'body.content')):
						$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['body']);
					endif;
					
					#if table, list, etc
					if ($this->isDirty($options, 'after')):
						$result .= $options['after'];
					endif;

					#if footer		
					$result .= String::insert("<:tag class=':baseClass :class'>", $options['footer']);
						$result .= String::insert("<:tag class=':baseClass :class' data-dismiss=':data-dismiss' type='button'>:content</:tag>", $options['footer']['close']);
						if ($this->isDirty($options, 'footer.content')):
							$result .= String::insert(":content", $options['footer']);
						endif;
					$result .= String::insert("</:tag>", $options['footer']);
					

				$result .= String::insert("</:tag>", $options['content']);
			$result .= String::insert("</:tag>", $options);
		if($id !== ''):
		$result .= String::insert("</:tag>", $options['wrapper']);
		endif;

		return $result;

	}


}
?>