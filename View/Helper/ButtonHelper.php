<?php

App::uses('BootstrapHelper','Bootstrap.View/Helper');

class ButtonHelper extends BootstrapHelper{

	public $helpers = ['Bootstrap.BootstrapHtml'];

	public $_options = 	[
							'button' => [
								'tag' => 'button',
								'baseClass' => 'btn btn-:context btn-:size btn-:block :disabled :active',
								'class' => '',
								'context' => 'default',
								'size' => 'md',
								'disabled' => '', # set to 'disabled' to disable
								'block' => 'noblock', #set to 'block' for block width
								'active' => '',
								'label' => ':label',
								'labelPrefix' => '', #allows for injection of things like glyphicons
								'labelSuffix' => '', #or dropdown carets
								'link' =>':link',
								'htmlAttributes'=>[
									'role' => 'button',
									'type' =>'button',
									//'data-toggle'=>'',
								],
								'wrapper'=>[
									'tag'=>'',
									'baseClass'=>'',
									'class' => '',
								]
							],

							'group'=>[
								'class' => '',
								'baseClass' => 'btn-group btn-group-:size btn-group-:orientation',
								'tag' => 'div',
								'size' => 'md',
								'orientation' => 'horizontal',

							],
							'dropdown' => [
								'split' => true,
								'button' => [
									'label' => ':label',
									'baseClass' => '',
									'class' => '',
									'link'=>':link'
									],
								'dropdown' => [
									'data-toggle' => 'dropdown',
									'baseClass' => 'dropdown-toggle',
									'class' => '',
									],
								'caret' => [
									'tag' => 'span',
									'baseClass' => 'caret',
									'class' => '',
									],
								'sr' => [
									'label' => 'Toggle Dropdown',
									'baseClass' => 'sr-only',
									'class' => '',
									'tag' => 'span'
									],
								'list' => [
									'tag' => 'ul',
									'baseClass' => 'dropdown-menu',
									'class' => '',
									'innerTag' => 'li',
									'innerClass' => '',
									],
								'wrapper'=>[
									'tag' => 'div', 
									'baseClass' => '', 
									'class'=>'' #add dropup to convert to dropup instead of dropdown
									] 
							]
						];


	public function button($data = [], $options = [], $type = 'button'){
		$this->fallbackIndex = 'label';
		if(is_string($options)):
			$this->setUnless($options,'link',$options);
		endif;
		if(is_string($data)):
			$this->setUnless($options,'label',$data);
		endif;

		$this->mergeOptions($type,$options);
		$this->insertData($options, $data);
		$this->insertData($options, $options);

		$result = ($options['wrapper']['tag'] !== '') ?  "<{$options['wrapper']['tag']} class='{$options['wrapper']['baseClass']} {$options['wrapper']['class']}'>" : '';
		
		switch ($type):
			case 'button':
				//$options['htmlAttributes'] = $this->_parseAttributes($options,$exclude = ['tag','baseClass','class','link','label','labelPrefix','labelSuffix','context','size','disabled','block','active','wrapper']);
				if($this->isDirty($options,'link')):
					$options['link'] = "onclick=\"window.location.assign('{$options['link']}')\"";
				else:
					$options['link'] = '';
				endif;
				if(!$this->isDirty($options,'id')):
					$options['id'] = $this->generateId();
				endif;
				$this->setHtmlAttributes($options);
				$result .= $this->safeInsertData("<:tag id=':id' class=':class' :htmlAttributes :link>", $options);
				$result .= $options['labelPrefix'];
				$result .= $options['label'];
				$result .= $options['labelSuffix'];
				$result .= $this->safeInsertData("</:tag>",$options);
			break;
		endswitch;
		
		$result .= ($options['wrapper']['tag'] !== '') ?  "</{$options['wrapper']['tag']}>" : '';

		return $result;
	}

	public function group($buttons, $options = []){
		$this->mergeOptions('group',$options);
		$this->insertData($options, $buttons);
		$this->insertData($options, $options);

		$result = $this->safeInsertData("<:tag class=':class'>",$options);
		$result .= (is_string($buttons)) ? $buttons : implode('', $buttons);
		$result .= $this->safeInsertData("</:tag>",$options);

		return $result;

	}

	public function dropdown($data = [], $list = [], $options = []){
		if(is_string($data)):
			$this->setUnless($data,'label',$data);
		endif;
		$this->mergeOptions('dropdown',$options);
		$this->insertData($options,$data);

		# a dropdown is a normal button with a caret added, and a list of options
		#   OR a grouped normal button with a second (caret) button and the same list of options
		# it's really just a specially pre-grouped button;

		$buttons = '';

		if($options['split']):
			#if split, split the label into its own button
			$this->mergeClasses($options['button']);
			$buttons .= $this->button($data,$options['button']);
			$options['button']['label'] = '';
			unset($options['button']['link']);
		else:

			unset($options['button']['link']);
		endif;
	
		#build dropdown toggle (set split=true above to break into sep button)		
		$button = [];
		$button['caret'] = $this->safeInsertData("<:tag class=':class'></:tag>",$options['caret']);
		$button['sr'] = $this->safeInsertData("<:tag class=':class'>:label</:tag>",$options['sr']);
		$button['label'] = $options['button']['label'];
		$button['labelSuffix'] = $this->safeInsertData(':caret :sr',$button);
		$button['htmlAttributes']['data-toggle'] = $options['dropdown']['data-toggle'];
		$this->mergeClasses($options['button']);
		$this->mergeClasses($options['dropdown']);
		$button['class'] = $options['button']['class'].' '.$options['dropdown']['class'];
		$buttons .= $this->button(' ',$button);
			
		$result = $buttons;

		$optList = "<:tag class=':class' role='menu'>";
			foreach($list as $listItem):
				$divider = ((is_string($listItem) && $listItem == 'divider') || empty($listItem)) ? 'divider' : false;
				$optList .= "<:innerTag class=':innerClass $divider'>";
					if(!$divider):
						$this->insertData($listItem,$data);
						$optList .= $listItem;
					endif;					
				$optList .= "</:innerTag>";
			endforeach;
		$optList .= "</:tag>";
		$this->insertData($optList,$options['list']);

		$result .= $optList;
		// $this->setUnless($groupOpts = [], 'class', $options['wrapper']['baseClass'].' '.$options['wrapper']['class']);
		$groupOpts['class'] = $options['wrapper']['baseClass'].' '.$options['wrapper']['class'];
		return $this->group($result, $groupOpts);

	}

}
?>