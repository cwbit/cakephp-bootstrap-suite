<?php


App::uses('BootstrapHelper', 'Bootstrap.View/Helper');
App::uses('BootstrapHelperEntity', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperEntityCollection', 'Bootstrap.View/Helper/Entity');
App::uses('BootstrapHelperWrappedEntityCollection', 'Bootstrap.View/Helper/Entity');

class BootstrapProgressBarHelper extends BootstrapHelperEntityCollection{
	protected $_entityClass = 'BootstrapProgressBarEntity';

}

class BootstrapProgressBarEntity extends BootstrapHelperWrappedEntityCollection{
	protected $_entityClass = 'BootstrapProgressBarSegment';
	protected $_options	= [
		'tag'=>'div',
		'baseClass'=>'progress',
	];
}

class BootstrapProgressBarSegment extends BootstrapHelperEntity{
	protected $_options = [
		'tag'=>'div',
		'baseClass'=>'progress-bar progress-bar-:context :active',
		'context'=>':context',
		'active'=>'',// '' or 'active'
		'prefix'=>':prefix',
		'label'=>':label',
		'suffix'=>':suffix',
		'htmlAttributes'=>[
			'style'=>'width: calc(100% * :current / :max);',
			'role'=>'progress-bar',
			'aria-valuenow'=>':current',
			'aria-valuemin'=>':min',
			'aria-valuemax'=>':max',
		],
	];
	protected $_pattern = "<:tag :htmlAttributes>:prefix:label:suffix</:tag>";
	protected $_contentToken = ':label';

	public function create($data = '', $options = [], $keyRemaps = false, $valueRemaps = false){
		if(is_string($data) && ( is_string($options) || is_numeric($options))):
			$this->setUnless($data,'label',$data);
			$this->setUnless($data,'current',$options);
			$options = [];
		endif;

		#DEFAULTS
		$this->setUnless($data,'current',0);
		$this->setUnless($data,'prefix','');
		$this->setUnless($data,'label','(:current/:max)');
		$this->setUnless($data,'suffix','');
		$this->setUnless($data,'min',0);
		$this->setUnless($data,'max',100);
		$this->setUnless($data,'context','success');
		#END DEFAULTS

		return parent::create($data, $options, $keyRemaps, $valueRemaps);
	}
}

// App::uses('BootstrapHelper', 'Bootstrap.View/Helper');

// class BootstrapProgressBarHelper extends BootstrapHelper{

// 	public $helpers = ['Bootstrap.BootstrapHtml','Bootstrap.Button'];

// 	public $_options = [
// 			'progress'=>[
// 				'tag'=>'div',
// 				'baseClass'=>'progress-bar progress-bar-:context :active',
// 				'context'=>':context',
// 				'active'=>'',// '' or 'active'
// 				'prefix'=>':prefix',
// 				'label'=>':label',
// 				'suffix'=>':suffix',
// 				'htmlAttributes'=>[
// 					'style'=>'width: calc(100% * :current / :max);',
// 					'role'=>'progress-bar',
// 					'aria-valuenow'=>':current',
// 					'aria-valuemin'=>':min',
// 					'aria-valuemax'=>':max',
// 				],
// 				'wrapper'=>[
// 					'tag'=>'div',
// 					'baseClass'=>'progress',
// 				]
// 			],
// 		];

// 		/**
// 		 * Creates a progress bar (or stacked bars)
// 		 * 
// 		 * Accepts calls in the following formats
// 		 * 
// 		 *	echo $this->BootstrapProgressBar->create('Health',50);
// 		 *	echo $this->BootstrapProgressBar->create(['current'=>25,'suffix'=>' gigawatts']);
// 		 *	echo $this->BootstrapProgressBar->create(['current'=>25,'max'=>50,'prefix'=>25,'label'=>' out of ','suffix'=>'50','context'=>'warning']);
// 		 *	echo $this->BootstrapProgressBar->create(['OK'=>['current'=>50],'Warning'=>['current'=>35,'context'=>'warning'],'Critical'=>['current'=>15,'context'=>'danger']]);		 *
// 		 *
// 		 * Any values that are missing will be replaced
// 		 *  
// 		 * @param mixed $data array of data values, string of prefix, array of arrays for stacked bars
// 		 * @param type $options any viable option setting from $this->_options
// 		 * @return type
// 		 */
// 	public function create($data, $options = []){
// 		// $data = (array)$data;
// 		if(is_string($data) && ( is_string($options) || is_numeric($options))):
// 			$this->setUnless($data,'label',$data);
// 			$this->setUnless($data,'current',$options);
// 			$options = [];
// 		endif;
// 		$data = (Hash::dimensions($data) > 1) ? $data : [0=>$data];
// 		$opts = (array)$options;
// 		$this->mergeOptions('progress',$opts);
// 		// $this->insertData($opts,$data); #wrapper opts dont have any :insert values, skip this step

// 		$result = [];
// 		$result[] = $this->safeInsertData("<:tag class=':class' :htmlAttributes>",$opts['wrapper']);
// 		foreach ($data as $key => $bar):
// 			$tempOpts = $options;
			
// 			if(is_string($key)):
// 				$this->setUnless($bar,'label',$key);
// 				if(is_string($bar) || is_numeric($bar)):
// 					$this->setUnless($bar,'current',$bar);
// 				endif;
// 			elseif(is_string($bar)):
// 				$this->setUnless($bar,'label',$bar);
// 			endif;

// 			/** DEFAULTS **/
// 			$this->setUnless($bar,'current',0);
// 			$this->setUnless($bar,'prefix','');
// 			$this->setUnless($bar,'label','(:current/:max)');
// 			$this->setUnless($bar,'suffix','');
// 			$this->setUnless($bar,'min',0);
// 			$this->setUnless($bar,'max',100);
// 			$this->setUnless($bar,'context','success');
					
// 			$this->mergeOptions('progress',$tempOpts);
// 			$this->insertData($bar,$bar);
// 			$this->insertData($tempOpts,$bar);

// 			$result[] = $this->safeInsertData("<:tag class=':class' :htmlAttributes>:prefix:label:suffix</:tag>",$tempOpts);
// 			unset($tempOpts);

// 		endforeach;

// 		$result[] = $this->safeInsertData("</:tag>",$opts['wrapper']);

// 		return implode(PHP_EOL, $result);

// 	}

// }
?>