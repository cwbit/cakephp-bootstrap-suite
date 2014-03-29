<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class ThumbnailHelper extends BootstrapHelper{

	public $helpers = ['Html','Bootstrap.Grid'];

	public $_options = 	[
							'thumbnail' => [
								'wrapper' => [
									'tag'=>'div',
									// 'class'=>'col-:resolution-:size',
									// 'baseClass'=>'',	
									'class'=>'',
									'resolution' => 'md',
									'size' => 12,
								],
								'inner'=> [
									'tag' => 'div',
									'baseClass' => 'thumbnail',
									'class'=>'',
								],							
								'image'=>[
									'tag'=>'img',
									'src' => ':image',
									'alt' => ':name',
									'baseClass' => 'img-responsive',
									'class'=>'',
								],
								'caption'=>[
									'tag' => 'div',
									'baseClass' => 'caption',
									'class'=>'',
									'content' => ':description',
									'innerTag' => 'p',
									'innerTagClass' => '',
									'after'=>':after',
								],
								'label' => [
									'tag' => 'h3',
									'baseClass' => '',	
									'class'=>'',
									'content' => ':name',
								],
							],
						];


	public function thumbnail($data = [], $options = []){
		
		$this->mergeOptions('thumbnail',$options);
		$this->insertData($options, $data);
		
		$result = $this->Grid->colBegin($options['wrapper']);
		$result .= $this->safeInsertData("<:tag class=':class' :htmlAttributes>", $options['inner']);
		$result .= $this->safeInsertData("<:tag src=':src' alt=':alt' class=':class' :htmlAttributes></:tag>", $options['image']);
		
		$result .= $this->safeInsertData("<:tag class=':class' :htmlAttributes>", $options['caption']);
		$result .= $this->safeInsertData("<:tag class=':class' :htmlAttributes>:content</:tag>", $options['label']);
		if($this->isDirty($options, 'caption.content')):
			$result .= $this->safeInsertData("<:innerTag class=':innerTagClass' :htmlAttributes>:content</:innerTag>", $options['caption']);
		endif;
		if ($this->isDirty($options, 'caption.after')):
			$result .= $this->safeInsertData(":after", $options['caption']);
		endif;
		$result .= $this->safeInsertData("</:tag>", $options['caption']);
		$result .= $this->safeInsertData("</:tag>", $options['inner']);
		$result .= $this->Grid->colEnd();

		return $result;
	}

}
?>