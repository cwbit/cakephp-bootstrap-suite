<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class GridHelper extends BootstrapHelper{

	public $_options = 	[
							'col' => [
								'baseClass' => 'col-:resolution-:size',
								'class'=>'',
								'tag' => 'div',
								'resolution' => 'md',
								'size' => 12,
								'options' => [],
							],
							'row' => [
								'tag' => 'div',
								'baseClass'=>'row',
								'class' => ''
							],
							'auto' => [
								'maxColWidth' => 12, // max col width is set by the Bootstrap CSS framework. do not change.
								'row'=>[],
								'col'=>[],
								'limit'=>4,	
								'method'=>'exact', //'exact' to RESULT(:limit), or 'fit' to use RESULT(:maxColWidth/:limit)


							]
						];


	public function row($content = '', $options = []){
		$this->mergeOptions('row',$options);

		$result = $this->rowBegin($options);
		$result .= $content;
		$result .= $this->rowEnd($options);

		return $result;
	}

	public function rowBegin($options = []){
		$this->mergeOptions('row',$options);
		return $this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options);
	}
	public function rowEnd($options = []){
		$this->mergeOptions('row',$options);
		return $this->safeInsertData("</:tag>",$options);
	}

	public function col($size, $content = '&nbsp;', $options = []){
		$options['size'] = $size;
		$this->mergeOptions('col',$options);
		$this->insertData($options,$options);

		$result = $this->colBegin($options);
		$result .= $content;
		$result .= $this->colEnd($options);

		return $result;
	}

	public function colBegin($options = []){
		if(is_int($options)):
			$size = $options;
			$options = [];
			$options['size'] = $size;
		endif;
		$this->mergeOptions('col',$options);
		$this->insertData($options,$options);
		return $this->safeInsertData("<:tag class=':class' :htmlAttributes>",$options);
	}
	public function colEnd($options = []){
		$this->mergeOptions('col',$options);
		return $this->safeInsertData("</:tag>",$options);
	}

	public function auto($data, $options = []){
		// if(Hash::dimensions($data) > 1):
			// return "{$this->alias} can only handle 1-dimensional arrays";	
		// endif;
		$this->mergeOptions('auto',$options);
		$this->insertData($options, $data);

		switch ($options['method']):
			case 'exact':
				$options['col']['size'] = $options['limit'];
				break;
			case 'fit':
				$options['col']['size'] = ceil($options['maxColWidth'] / $options['limit']); 
				break;
			default:
				$options['col']['size'] = $options['limit'];
				break;
		endswitch;

		$result = [];
		$count = 0;
		foreach($data as $key => $value):
			if ($count % $options['maxColWidth'] == 0):
				$result[] = $this->rowBegin();
			endif;
			$count += $options['col']['size'];

			$result[] = $this->col($options['col']['size'],$value,$options['col']);

			if ($count % $options['maxColWidth'] == 0 && $count != 0):
				$result[] = $this->rowEnd();
			endif;
		endforeach;

		return implode(PHP_EOL, $result);

	}


}