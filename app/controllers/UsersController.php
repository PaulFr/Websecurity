<?php

class UsersController extends AppController{
	
	public $requiredModels = array('User');
	public $requiredPlugins = array('Session');

	public function login(){
		if(isset($this->Session->get('User')->login))
			$this->response->redirect('');
		if(isset($this->request->datas['User'])){
			$this->User->validate($this->request->datas['User'], 'Login');
			if(empty($this->User->validateErrors['Login'])){

				$result = $this->User->checkPassword($this->request->datas['User']['login'], $this->request->datas['User']['password']);
				if($result){
					$this->User->doConnection($result->id);
					$this->Session->setFlash('Vous êtes maintenant connecté en tant que '.$result->login.'.');
					$this->Session->set('User', $result);
					$this->Session->set('Token', uniqid());
					$this->response->redirect('');
				}else{
					$this->Session->setFlash('La connexion a échouée. Veuillez vérifier les informations que vous avez entrées.');
				}

			}
		}
	}

	public function logout($token){
		if(!isset($this->Session->get('User')->login) || $token != $this->Session->get('Token'))
			Core::throwError(404);
		$this->Session->setFlash('Vous êtes maintenant déconnecté, '.$this->Session->get('User')->login.'.');
		$this->Session->delete('User');
		$this->response->redirect('users/login');
	}

	public function register(){
		if(isset($this->request->datas['User'])){
			$this->User->validate($this->request->datas['User'], 'Register');
			if(empty($this->User->validateErrors['Register'])){
				$user = $this->User->register($this->request->datas['User']);
				if(!$user){
					$this->Session->setFlash('L\'inscription a échouée !');
				}else{
					$this->Session->set('User', $user);
					$this->Session->set('Token', uniqid());
					$this->Session->setFlash('Félicitation '.$user->login.' ! Vous êtes maintenant inscrit !');
					$this->response->redirect('');
				}
			}
		}
	}

	public function account(){
		$this->response->redirect('');
		$droits = 0;
		$droits |= CAN_ACCESS_ADMIN | CAN_MANAGE_DIARY | CAN_MANAGE_BLOG | CAN_MANAGE_TUTORIELS | CAN_MANAGE_COMMENTS | CAN_MANAGE_WORKS | CAN_MANAGE_FORUM | CAN_MODERATE_FORUM | CAN_MANAGE_GROUPS | CAN_MANAGE_USERS;
		echo $droits;
	}


}

?>