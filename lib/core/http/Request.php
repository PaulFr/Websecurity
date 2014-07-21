<?php

class Request{
	
	public $target;
	public $controller;
	public $method;
	public $datas;
	public $params;
	public $short;

	public function __construct($auto=true){
		if($auto){
			$this->target = trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/', '/');
			$this->datas  = $_REQUEST;
			Router::parse($this);
		}
	}

	public function getCookieData($cookieName){
		return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : false;
	}

	public function getPostData($postName){
		return isset($_POST[$postName]) ? $_POST[$postName] : false;
	}

	public function getGetData($getName){
		return isset($_GET[$getName]) ? $_GET[$getName] : false;
	}

	public function getIP(){
		return $_SERVER['REMOTE_ADDR'];
	}

}

?>