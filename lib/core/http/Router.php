<?php
class Router{
	

	static $routes = array(); 
	static $alias = array();
	static $prefixes = array(); 
	static $vars = array(); 

	/**
	* Ajoute un prefix au Routing
	**/
	static function prefix($url,$prefix){
		self::$prefixes[$url] = $prefix; 
	}

	static function variable($var){
		self::$vars[] = $var;
	}

	/**
	* Permet de parser une url
	* @return tableau contenant les paramètres
	**/
	static function parse($request){

		$request->origin = $request->target;
		
		foreach(Router::$vars as $v){
			if(isset($request->datas[$v])){
				$request->target = trim($request->datas[$v], '/');
				break;
			}
		}

		if(empty($request->target)){
			$request->target = Router::$routes[0]['url']; 
		}else{
			$match = false; 
			foreach (Router::$alias as $v) {
				if(preg_match($v['redirreg'],$request->target,$matchA)){

					$request->target = substr($matchA['args'],1);
					break;
				}
			}
			foreach(Router::$routes as $v){
				if(!$match && preg_match($v['redirreg'],$request->target,$match)){
					$request->target = $v['origin'];
					foreach($match as $k=>$v){
						$request->target = str_replace(':'.$k.':',$v,$request->target); 
					} 
					$match = true; 
				}
			}
		}
		
		$params = explode('/',$request->target);
		if(in_array($params[0],array_keys(self::$prefixes))){
			$request->short = self::$prefixes[$params[0]];
			array_shift($params); 
		}
		$request->controller = $params[0];
		$request->method = isset($params[1]) ? $params[1] : 'index';

		foreach(self::$prefixes as $k=>$v){
			if(strpos($request->method,$v.'_') === 0){
				$request->short = $v;
				$request->method = str_replace($v.'_','',$request->method);  
			}
		}
		$request->params = array_slice($params,2);
		if(isset($request->short) && $request->short != 'call'){
			$request->method = $request->short.'_'.$request->method;
		}

		return true; 
	}


	/**
	* Permet de connecter une url à une action particulière
	**/
	static function connect($redir,$url){
		self::$routes[] = self::parseUrl($redir, $url); 
	}
	/**
	* Permet de connecter une url à une action particulière (alias)
	**/
	static function alias($redir,$url){
		self::$alias[] = self::parseUrl($redir, $url); 
	}

	private static function parseUrl($redir, $url){
		$r = array();
		$r['params'] = array();
		$r['url'] = $url;  

		$r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/','${1}:(?P<${1}>${2})',$url);
		$r['originreg'] = str_replace('/*','(?P<args>/?.*)',$r['originreg']);
		$r['originreg'] = '/^'.str_replace('/','\/',$r['originreg']).'$/'; 
		// MODIF
		$r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/',':${1}:',$url);
		$r['origin'] = str_replace('/*',':args:',$r['origin']); 

		$params = explode('/',$url);
		foreach($params as $k=>$v){
			if(strpos($v,':')){
				$p = explode(':',$v);
				$r['params'][$p[0]] = $p[1]; 
			}
		} 

		$r['redirreg'] = $redir;
		$r['redirreg'] = str_replace('/*','(?P<args>/?.*)',$r['redirreg']);
		foreach($r['params'] as $k=>$v){
			$r['redirreg'] = str_replace(":$k","(?P<$k>$v)",$r['redirreg']);
		}
		$r['redirreg'] = '/^'.str_replace('/','\/',$r['redirreg']).'$/';

		$r['redir'] = preg_replace('/:([a-z0-9]+)/',':${1}:',$redir);
		$r['redir'] = str_replace('/*',':args:',$r['redir']); 

		return $r;
	}

	/**
	* Permet de générer une url à partir d'une url originale
	* controller/action(/:param/:param/:param...)
	**/
	static function url($url = ''){
		trim($url,'/'); 
		foreach(self::$routes as $v){
			if(preg_match($v['originreg'],$url,$match)){
				$url = $v['redir']; 
				foreach($match as $k=>$w){
					$url = str_replace(":$k:",$w,$url); 
				}
			}
		}
		foreach(self::$prefixes as $k=>$v){
			if(strpos($url,$v) === 0){
				$url = str_replace($v,$k,$url); 
			}
		}
		return URL.'/'.$url; 
	}

	static function wwwroot($url, $force = false){
		trim($url,'/');
		if(file_exists(APPROOT.SEPARATOR.'www'.SEPARATOR.$url) || $force)
			return URL.'/'.$url; 
		else
			return '';
	}

}