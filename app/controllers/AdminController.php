<?php

class AdminController extends AppController{
	
	public $requiredPlugins = array('Session');

	public function index(){
		if (isset($this->Session->get('User')->rights) && $this->Session->get('User')->rights & Rights::get('ACCESS_ADMIN')){
			
		}else{
			$this->response->redirect('');
		}
	}

	public function call_menu(){
		$menu = '';
		$droits = isset($this->Session->get('User')->rights) ? $this->Session->get('User')->rights : 0;
		foreach (Rights::get() as $k => $v){
			if ($droits & $v['value']){
				if(!(isset($v['admin']) && $v['admin'] == false)){
					$menu .= '<li><a href="'.Router::url($v['url']).'">'.$v['name'].'</a></li>';
				}
			}
		}
		return '<ul>'.$menu.'</ul>';
	}

}

?>