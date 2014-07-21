<?php

class TwitterController extends AppController{
	
	public function call_last($pseudo, $nb=2){
		$this->loadModel('Twitter');
		return $this->Twitter->getTweetsFrom($pseudo, $nb);
	}

}


?>