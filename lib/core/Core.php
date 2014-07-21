<?php

class Core{
	
	public static $request;
	public static $response;


	public static function launch($request = null){
		self::$request  = $request == null ? new Request() : $request;
		self::$response = new Response();
		$controllerName = ucfirst(self::$request->controller).'Controller';
		$controllerPath = APPROOT.SEPARATOR.'controllers'.SEPARATOR.$controllerName.'.php';
		if(file_exists($controllerPath)){
			require_once($controllerPath);
			$controller = new $controllerName(self::$request, self::$response);
			$reflection = new ReflectionClass($controllerName);
			$controllerMethods = self::getControllerMethods($reflection,$controllerName);
			if(in_array(self::$request->method, $controllerMethods)){
				if(self::checkRequiredParams($reflection)){
					call_user_func_array(array($controller,self::$request->method), self::$request->params);
					$controller->view->render(strtolower(self::$request->controller).SEPARATOR.strtolower(self::$request->method));
				}else{
					//echo 'manque arguments';
					self::throwError(404);
				}
			}else{
				//echo 'action existe pas';
				self::throwError(404);
			}
		}else{
			//echo 'controller existe pas';
			self::throwError(404);
		}
	}

	public static function throwError($code){
		$errorRequest = new Request(false);
		$errorRequest->controller = 'errors';
		$errorRequest->method = 'index';
		$errorRequest->params = array($code);
		self::launch($errorRequest);
		exit;
	}

	public static function isCached($filename){
		return file_exists(CACHE.SEPARATOR.$filename);
	}
	public static function cachedSince($filename){
		return time() - fileatime(CACHE.SEPARATOR.$filename);
	}
	public static function getCachedFile($filename){
		return file_get_contents(CACHE.SEPARATOR.$filename);
	}
	public static function deleteCachedFile($filename){
		unlink(CACHE.SEPARATOR.$filename);
	}
	public static function writeCache($filename, $content){
		if(self::isCached($filename)){
			self::deleteCachedFile($filename);
		}
		$f = fopen(CACHE.SEPARATOR.$filename, 'a+');
		fwrite($f, $content);
		fclose($f);
	}


	public static function checkRequiredParams($reflection){
		$valide = true;
		$paramsInfos = $reflection->getMethod(self::$request->method)->getParameters();
		for ($i=0; $i < count($paramsInfos); $i++) { 
			if(!$paramsInfos[$i]->isOptional() && !isset(self::$request->params[$i])){
				$valide = false;
			}
		}
		return $valide;
	}

	public static function getControllerMethods($reflection,$controllerName){
		$methods = array();
		foreach ($reflection->getMethods() as $k => $v) {
			if($v->class == $controllerName){
				$methods[] = $v->name;
			}
		}
		return $methods;
	}

}

?>