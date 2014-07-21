<?php

class ErrorsController extends AppController{
	
	public function index($codeError){
		$message = '';
		if($codeError == 404){
			$message = 'Nous sommes désolés, mais cette page ne semble pas exister.';
		}else{
			$message = 'Un problème a été détecté. Nous sommes dans l\'incapacité de vous faire accéder à cette page.';
		}
		$this->view->bind('message', $message);
	}

}

?>