<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class WellHelper extends BootstrapHelper{

	public $_options = 	[
							'well' => [
								'tag' => 'div',
								'baseClass' => 'well well-:size',
								'class' => '',
								'size'=>'md',
								'content' => ':description',
							],
						];


	public function create($data = [], $options = []){
		
		$this->mergeOptions('well',$options);

		if(is_string($data)):
			$data = ['description'=>$data];
		endif;

		$this->insertData($options, $data);
		$this->insertData($options, $options);

		$result  = 	"<{$options['tag']} class='{$options['baseClass']} {$options['class']}'>";
		$result .=		$options['content'];
		$result .=	"</{$options['tag']}>";
		return $result;
	}

}
?>