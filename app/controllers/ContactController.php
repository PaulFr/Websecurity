<?php

class ContactController extends AppController{
	public function index(){
		if(isset($this->request->datas['Contact'])){
			$this->loadModel('Contact');
			$this->Contact->validate($this->request->datas['Contact'], 'Contact');
		}
	}
}

?>