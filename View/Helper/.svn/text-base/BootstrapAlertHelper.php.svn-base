<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class BootstrapAlertHelper extends BootstrapHelper{

	public $_options = [
			'alert'=>[
				'tag' => 'div',
				'baseClass' => 'alert alert-:type :dismissable',
				'class' => '',
				'type' => 'info',
				'dismissable' => 'alert-dismissable',
				'title' => '',
				'titleTag' => 'strong',
				'content' => ':content'
			]
		];

	public function alert($data, $options = []){
		if(is_string($data) && is_string($options)):
			$data = ['title'=>$data, 'content'=>$options];
			$options = [];
		elseif(is_string($data)):
			$data = ['content'=>$data];
		endif;

		$this->mergeOptions('alert',$options);

		

		$this->insertData($options, $data);
		$this->insertData($options, $options);

		$result = '';
		$result .= "<:tag class=':baseClass :class'>";
		if($options['dismissable'] !== false):
			$result .=  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		endif;
		if($options['title'] != '') : 
			$result .= "<:titleTag>:title</:titleTag> ";
		endif;
		$result .= ":content";
		$result .= "</:tag>";

		$this->insertData($result, $options);

		return $result;
	}

}
?>