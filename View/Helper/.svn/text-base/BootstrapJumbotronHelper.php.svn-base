<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class BootstrapJumbotronHelper extends BootstrapHelper{

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
								'title' => [
									'tag' => 'h1',
									'class' => '',	
									'content' => ':name',
								],
							],
						];


	public function create($data = [], $options = []){
		if (is_string($data)):
			$this->setUnless($data,'title',$data);
			if (is_string($options)):
				$this->setUnless($data,'content',$options);
				$options = [];
			endif;
		endif;
		$this->mergeOptions('jumbotron',$options);
		
		if (isset($data['title'])):
			$options['title']['content'] = $data['title'];
		endif;
		if (isset($data['content'])):
			$options['content']['content'] = $data['content'];
		endif;

		$this->insertData($options, $data);

		$result  = 	"<{$options['wrapper']['tag']} class='{$options['wrapper']['class']}'>";
		$result .=		$this->Grid->rowBegin();
		$result .=	"		<{$options['title']['tag']} class='{$options['title']['class']}'>{$options['title']['content']}</{$options['title']['tag']}>";
		$result .=	"		<{$options['content']['tag']} class='{$options['content']['class']}'>{$options['content']['content']}</{$options['content']['tag']}>";
		$result .=		$this->Grid->rowEnd();
		$result .=	"</{$options['wrapper']['tag']}>";
		return $result;
	}

}
?>