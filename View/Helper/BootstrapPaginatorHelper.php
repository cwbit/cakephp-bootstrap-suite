<?php

App::uses('BootstrapHelper','Bootsrap.View/Helper');
App::uses('PaginatorHelper','View/Helper');

class BootstrapPaginatorHelper extends BootstrapHelper{

	public $helpers = ['Paginator','Bootstrap.Grid'];

	public $_options = [
			'limit'=>[
				'baseClass' =>'pagination',
				'class'=>'',
				'tag'=>'ul',
				'innerTag'=>'li',
				'innerTagBaseClass' => '',
				'innerTagClass' =>'',
				'limit' => ['25','50','75','100'],
			],
		];

	public $_args = [
		'foo'=>[  #$this->Html->foo
			0=>[	#second argument (options) defaults
				'bar'
			],
		],
	];

	/**
	 * This function is called by PHP when a function is called on SELF that doesn't exist in SELF, or PARENT
	 * We are using this function to provide an injection override for PaginatorHelper.
	 * 
	 * Use BootstrapPaginator just like the PaginatorHelper. If the method doesn't exist in SELF/PARENT (e.g. link()) then
	 * __call will be used to look in the PaginatarHelper 
	 * 
	 * Use the $_args array to inject default function options into function call
	 * 	e.g.
	 * 		$_args = [
	 * 				'end'=>[
	 * 					0 => 'GO BABY GO!'
	 * 				]
	 * 			]
	 * will inject the string 'GO BABY GO!' into the first parameter of Paginator->link('GO BABY GO!')
	 * 
	 * For function declarations to inject/override, look here --> http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html
	 * 
	 * @param type $method 
	 * @param type $args 
	 * @return type
	 */
	public function __call($method, $args){
		# inject default $_args into the $args parameters (see var itself for details
		if( isset($this->_args[$method]) ):
			$args = Hash::merge($this->_args[$method], $args);
			ksort($args);
		endif;
		
		if(method_exists('PaginatorHelper', $method)):
			// $c = $this->_View->loadHelper('Bootstrap.BoostCakeHtml');
			return call_user_func_array([$this->Paginator,$method], $args);
		endif;
	}

	public function limit($options = []){
		$this->mergeOptions('limit', $options);
		$this->insertData($options, $options);
		$result = "<{$options['tag']} class='{$options['baseClass']} {$options['class']}'>";
			foreach ($options['limit'] as $limit):
				$result .= "<{$options['innerTag']} class='{$options['innerTagBaseClass']} {$options['innerTagClass']}'>";
				$result .= $this->Paginator->link($limit,array('limit'=>$limit));
				$result .="</{$options['innerTag']}>";
			endforeach;
		$result .= "</{$options['tag']}>";
		return $result;
	}

	public function footer($pageCount = null, $currentPage = null, $deviation = 2, $showFirst = true, $showLast = true){

		$pageCount = (!is_null($pageCount)) ? $pageCount : intval($this->Paginator->counter(array('format'=>'%pages%')));
		$currentPage = (!is_null($currentPage)) ? $currentPage : $this->Paginator->current();
	
		$result = $this->Grid->rowBegin();
			$result .= $this->Grid->colBegin(['size'=>'2','class'=>'container']);
				$result .= "<ul class='pager'>";
					$page = $currentPage - 1;
					$class = ($page <= 0) ? 'disabled' : '';
					$result .= "<li class='previous $class'>";
					$result .= $this->Paginator->link('Previous',array('page'=>$page));
					$result .= "</li>";
				$result .= "</ul>";
			$result .= $this->Grid->colEnd();
			$result .= $this->Grid->colBegin(['size'=>'8','class'=>'container']);
				$result .= "<center><ul class='pagination'>";
					for ($i=1; $i <= $pageCount; $i++):
						if (($showFirst && $i==1) || ($showLast && $i==$pageCount) || (abs($currentPage - $i) <= $deviation)):
							//if what we are about to print is out of range and is the last and $showLast then print ... before
							if ($showLast && (abs($currentPage - $pageCount) > $deviation) && ($i==$pageCount)):
								$result .= "<li class='disabled'>";
								$result .= "<a href='#'>...</a>";
								$result .= "</li>";
							endif;

							//print number link
							$class = ($currentPage == $i) ? 'active' : '';
							$result .= "<li class='$class'>";
							$result .= $this->Paginator->link($i,array('page'=>$i));
							$result .= "</li>";

							//if what we just printed was out of range and is first and $showFirst then print ... after it
							if ($showFirst && (abs($currentPage - $deviation) > 1) && ($i==1)):
								$result .= "<li class='disabled'>";
								$result .= "<a href='#'>...</a>";
								$result .= "</li>";
							endif;
						else:
							//else, skip - not in range. increase $devation to widen range
						endif; 	
					endfor;
				$result .= "</ul></center>";			
			$result .= $this->Grid->colEnd();
			$result .= $this->Grid->colBegin(['size'=>'2','class'=>'container']);
				$result .= "<ul class='pager'>";
					$page = $currentPage + 1;
					$class = ($page > $pageCount) ? 'disabled' : '';
					$result .= "<li class='next $class'>";
					$result .= $this->Paginator->link('Next',array('page'=>$page));
					$result .= "</li>";
				$result .= "</ul>";
			$result .= $this->Grid->colEnd();
		$result .= $this->Grid->rowEnd();

		return $result;
	}
	
}

?>