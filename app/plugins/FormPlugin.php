<?php

class FormPlugin extends AppPlugin{
	
	private $name;
	private $fields = array();
	private $autoComplete = false;
	private $compilatedFields = array();
	private $url;
	private $method;
	private $separator = '';
	private $validateName;

	public function create($formName, $url='', $method='GET', $validateName=null){
		$formName = ucfirst($formName);
		$this->name = $formName;
		$this->url = $url;
		$this->method = $method;
		if(!isset($validateName)){
			$this->validateName = $this->name;
		}else{
			$this->validateName = $validateName;
		}
	}

	public function separator($separator){
		$this->separator = $separator;
	}

	private function checkInit(){
		if(!$this->name)
			throw new Exception('Form is not init.');
	}

	public function addField($name, $options = array()){
		$this->checkInit();
		$this->fields[$name] = $options;
	}

	public function autoComplete($bool){
		$autoComplete = $bool;
	}

	public function build($labelSend='Envoyer !'){
		foreach ($this->fields as $k => $v) {

			$id = $this->name.'-'.$k;
			$name = $this->name.'['.$k.']';
			$label = '<label for="'.$id.'">'.(isset($v['label']) ? $v['label'] : ucfirst($k)).' : </label>';
			if(isset($v['type']) && $v['type'] == 'hidden') $label = '';
			$field = '';

			$value = isset($this->controller->request->datas[$this->name][$k]) ? htmlspecialchars($this->controller->request->datas[$this->name][$k]) : (isset($this->controller->{$this->name}) ? (isset($this->controller->{$this->name}->prefilled[$k]) ? $this->controller->{$this->name}->prefilled[$k] : (isset($v['value']) ? $v['value'] : '')) : (isset($v['value']) ? $v['value'] : ''));

			if(!isset($v['type']) || $v['type'] == 'password' || $v['type'] == 'text' || $v['type'] == 'hidden' || $v['type'] == 'file'){
				$field .= $label.' <input name="'.$name.'" id="'.$id.'" type="'.(isset($v['type']) ? $v['type'] : 'text').'" value="'.$value.'" />';
			}else if($v['type'] == 'textarea'){
				$field .= $label.' <textarea name="'.$name.'" id="'.$id.'">'.$value.'</textarea>';
			}else if($v['type'] == 'select'){
				$options = array();
				foreach ($v['options'] as $optionValue => $optionName) {
					$options[] = '<option value="'.$optionValue.'" '.($value == $optionValue ? 'selected="selected"' : '').'>'.$optionName.'</option>';
				}
				$field .= $label.' <select name="'.$name.'" id="'.$id.'">'.implode("\n", $options).'</select>';
			}else if($v['type'] == 'radio'){
				$options = array();
				foreach ($v['options'] as $optionValue => $optionName) {
					$options[] = '<input type="radio" name="'.$name.'" value="'.$optionValue.'" '.($value == $optionValue ? 'checked="checked"' : '').'> '.$optionName.' ';
				}
				$field .= $label.' '.implode("\n", $options);
			}else if($v['type'] == 'checkbox'){
				$field .= $label.' <input type="hidden" name="'.$name.'" value="0"><input name="'.$name.'" value="1" id="'.$id.'" type="checkbox" '.(!empty($value) ? 'checked="checked"' : '').'>';
			}

			if(isset($this->controller->{$this->name})){
				$model = $this->controller->{$this->name};
				if(isset($model->validateErrors[$this->validateName][$k])){
					$field .= '<span class="error">'.$model->validateErrors[$this->validateName][$k].'</span>';
				}
			}

			$this->compilatedFields[] = $field;
		}

		return '<form action="'.$this->url.'" method="'.$this->method.'" id="'.$this->name.'Form">
		'.implode($this->separator."\n",$this->compilatedFields).$this->separator.'
		<input type="submit" class="button orange" value="'.$labelSend.'" name="'.$this->name.'[sended]">
		</form>';
	}

}

?>