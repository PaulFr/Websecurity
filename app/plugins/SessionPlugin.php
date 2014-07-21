<?php

class SessionPlugin extends AppPlugin{
	
	public function get($key = null){
		if($key == null){
			return $_SESSION;
		}else{
			return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
		}
	}

	public function set($key, $value){
		$_SESSION[$key] = $value;
	}

	public function delete($key = null){
		if($key == null){
			$_SESSION = array();
			session_destroy();
		}else{
			$_SESSION[$key] = array();
			unset($_SESSION[$key]);
		}
	}

	public function setFlash($message,$type = ''){
		$this->set('flash',array(
			'message' => $message,
			'type'	=> $type
		));
	}

	public function flash(){
		if(isset($_SESSION['flash']['message'])){
			$html = '<div class="notification '.$_SESSION['flash']['type'].'"><p>'.$_SESSION['flash']['message'].'</p></div>'; 
			$this->delete('flash'); 
			return $html; 
		}else{
			return false;
		}
	}

}

?>