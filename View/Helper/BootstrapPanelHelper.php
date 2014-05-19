<?php

App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

class BootstrapPanelHelper extends BootstrapHelper{

	public $_options = [
			'panel' =>[
				'tag'=>'div',
				'baseClass'=> 'panel panel-:context',
				'class'=>'',
				'context'=>'default',
				'heading'=>[
					'tag'=>'div',
					'baseClass'=>'panel-heading',
					'class' => '',
					'content'=> ':heading',
					'title'=>[
						'tag'=>'h3',
						'baseClass'=>'panel-title',
						'class'=>'',
						'content'=>':title'
					]
				],
				'body'=>[
					'tag'=>'div',
					'baseClass' => 'panel-body',
					'class'=> '',
					'content' => ':body',
				],
				'after' => ':after',
				'footer'=>[
					'tag'=>'div',
					'baseClass' => 'panel-body',
					'class'=> '',
					'content' => ':footer',
				],
			]
		];

	public function create($data, $options = []){
		if (is_string($data)):
			$temp = [];
			$temp['body'] = $data;
			$data = $temp;
		endif;

		$this->mergeOptions('panel',$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		$result = '';

		$result .= String::insert("<:tag class=':baseClass :class'>", $options);
		#if header (and/or title)
		if ($this->isDirty($options,'heading.content') || $this->isDirty($options,'heading.title.content')):
			$result .= String::insert("<:tag class=':baseClass :class'>", $options['heading']);
				if($this->isDirty($options,'heading.title.content')):
					$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['heading']['title']);
				else:
					$result .= String::insert(":content", $options['heading']);
				endif;
			$result .= String::insert("</:tag>", $options['heading']);
		endif;
		#if body
		if ($this->isDirty($options, 'body.content')):
			$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['body']);
		endif;
		#if table, list, etc
		if ($this->isDirty($options, 'after')):
			$result .= $options['after'];
		endif;
		#if footer
		if ($this->isDirty($options, 'footer.content')):
			$result .= String::insert("<:tag class=':baseClass :class'>:content</:tag>", $options['footer']);
		endif;

		$result .= String::insert("</:tag>", $options);

		return $result;

	}


}
?>