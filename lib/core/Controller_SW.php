<?php

class Controller_SW{
	
	public $request;
	public $response;
	public $view;
	public $requiredPlugins;
	public $defaultRequiredPlugins;
	public $requiredModels;

	public function __construct($request, $response){
		$this->request  = $request;
		$this->response = $response;
		$this->view = new AppView($this);
		$this->view->setLayout(Config::get('DEFAULT_LAYOUT'));
		
		if(empty($this->requiredPlugins)) $this->requiredPlugins = array();
		if(empty($this->defaultRequiredPlugins)) $this->defaultRequiredPlugins = array();
		$this->requiredPlugins = array_merge($this->requiredPlugins, $this->defaultRequiredPlugins);
		if(isset($this->requiredPlugins)){
			foreach ($this->requiredPlugins as $v) {
				$this->loadPlugin($v);
			}
		}
		if(isset($this->requiredModels)){
			foreach ($this->requiredModels as $v) {
				$this->loadModel($v);
			}
		}
	}

	public function loadModel($modelName){
		$modelName = ucfirst($modelName);
		$modelPath = APPROOT.SEPARATOR.'models'.SEPARATOR.$modelName.'.php';
		if(file_exists($modelPath)){
			require_once($modelPath);
			$this->$modelName = new $modelName();
		}
	}

	public function loadPlugin($pluginName){
		$pluginName = ucfirst($pluginName);
		$pluginClass = $pluginName.'Plugin';
		$pluginPath = APPROOT.SEPARATOR.'plugins'.SEPARATOR.$pluginClass.'.php';
		if(file_exists($pluginPath)){
			require_once($pluginPath);
			$this->$pluginName = new $pluginClass($this);
		}
	}

	public function call($controller, $action, $params= array()){
		$controllerName = ucfirst($controller).'Controller';
		$controllerPath = APPROOT.SEPARATOR.'controllers'.SEPARATOR.$controllerName.'.php';
		if(file_exists($controllerPath)){
			require_once($controllerPath);
			$controllerApp = new $controllerName($this->request, $this->response);
			$action = 'call_'.$action;
			return call_user_func_array(array($controllerApp,$action), $params);
		}else{
			throw new Exception('Unknown controller.');
		}
	}

	public function callFromLayout($controller, $action, $params= array()){
		$controllerName = ucfirst($controller).'Controller';
		$controllerPath = APPROOT.SEPARATOR.'controllers'.SEPARATOR.$controllerName.'.php';
		if(file_exists($controllerPath)){
			require_once($controllerPath);
			$controllerApp = new $controllerName($this->request, $this->response);
			call_user_func_array(array($controllerApp,$action), $params);
			return $controllerApp->view->render(strtolower($controller).SEPARATOR.strtolower($action), true);
		}else{
			throw new Exception('Unknown controller.');
		}
	}

}