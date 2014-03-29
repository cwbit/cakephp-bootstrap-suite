<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class JumbotronHelper extends BootstrapHelper{

	public $helpers = ['Bootstrap.Grid'];

	public $_options = 	[
							'jumbotron' => [
								'wrapper' => [
									'tag'=>'div',
									'class'=>'jumbotron container',
								],
								'content'=>[
									'tag' => 'p',
									'class' => '',
									'content' => ':description',
								],
								'label' => [
									'tag' => 'h1',
									'class' => '',	
									'content' => ':name',
								],
							],
						];


	public function jumbotron($data = [], $options = []){
		
		$this->mergeOptions('jumbotron',$options);//$options = array_replace_recursive($this->_options['thumbnail'],$options);

		$this->insertData($options, $data);

		$result  = 	"<{$options['wrapper']['tag']} class='{$options['wrapper']['class']}'>";
		$result .=		$this->Grid->rowBegin();
		$result .=	"		<{$options['label']['tag']} class='{$options['label']['class']}'>{$options['label']['content']}</{$options['label']['tag']}>";
		$result .=	"		<{$options['content']['tag']} class='{$options['content']['class']}'>{$options['content']['content']}</{$options['content']['tag']}>";
		$result .=		$this->Grid->rowEnd();
		$result .=	"</{$options['wrapper']['tag']}>";
		return $result;
	}

}
?>