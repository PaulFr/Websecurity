<?php

class View_SW{
	
	public $controller;
	public $layout;
	public $variables;
	public $rendered;
	public $titleForLayout;
	public $contentForLayout;
	public $files = array();

	public function __construct($controller){
		$this->controller = $controller;
		$this->variables = array();
		$this->rendered  = false;
		$this->titleForLayout = Config::get('SITE_NAME');
	}

	public function bind($key, $value = null){
		if(is_array($key)){
			$this->variables += $key;
		}else{
			$this->variables[$key] = $value;
		}
	}

	public function setLayout($layout){
		$this->layout = $layout;
	}

	public function setTitle($title){
		$this->titleForLayout = $title;
	}

	public function addJsFile($path){
		$this->files['js'][] = '<script type="text/javascript" src="'.$path.'"></script>';

	}

	public function getJsFiles(){
		return isset($this->files['js']) ? implode("\n", $this->files['js']) : '';
	}

	public function render($view, $withoutLayout = false){
		if($this->rendered)
			return false;
		$view = str_replace('_', SEPARATOR, $view);
		$viewPath = APPROOT.SEPARATOR.'views'.SEPARATOR.$view.'.php';
		$layoutPath = APPROOT.SEPARATOR.'views'.SEPARATOR.'layouts'.SEPARATOR.$this->layout.'.php';
		if(file_exists($viewPath)){
			extract($this->variables);
			ob_start();
			require_once($viewPath);
			$this->contentForLayout = ob_get_clean();
			if($withoutLayout){
				$rendered = true;
				return $this->contentForLayout;
			}
			if(!$this->layout || !file_exists($layoutPath))
				echo $this->contentForLayout.'<p>PENSEZ A CREER UN LAYOUT !</p>';
			else{
				require_once($layoutPath);
			}
			$this->rendered = true;
			return true;
		}else{
			echo '<p>Attention, pensez à créer la vue '.$view.' !</p>';
		}
	}

}