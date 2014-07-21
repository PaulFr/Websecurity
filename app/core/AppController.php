<?php

class AppController extends Controller_SW{
	
	public $requiredPlugins = array('Session');

	public function __construct($request, $response){
		parent::__construct($request, $response);
		if($request->short == 'admin'){
			if(!(isset($this->Session->get('User')->rights) && $this->Session->get('User')->rights & Rights::get('ACCESS_ADMIN'))){
				Core::throwError(404);
			}
		}
	}

}