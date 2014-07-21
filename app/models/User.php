<?php

class User extends AppModel{
	
	public $validateRules = array(
		'Login' => array(
			'login' => array(
				'rule' => '^([a-zA-Z0-9-_]{3,})$',
				'message' => 'Votre pseudo doit contenir au minimum 3 caractères.',
			),
			'password' => array(
				'rule' => '^.{4,}$',
				'message' => 'Votre mot de passe doit être composé d\'au moins 4 caractères.',
			),
		),
		'Register' => array(

			'login' => array(
				'rule' => '^([a-zA-Z0-9-_]{3,})$',
				'message' => 'Votre pseudo doit contenir 3 caractères minimum (composé de lettres alphanumériques)'
			), 
			'password' => array(
				'rule' => '^.{4,}$',
				'message' => 'Votre mot de passe doit être composé d\'au moins 4 caractères',
			), 
			'passwordcheck' => array(
				'rule' => 'same',
				'message' => 'Le mot de passe n\'est pas identique au premier',
				'relation' => 'password',
			),
			'email' => array(

				'rule' => '([a-zA-Z0-9\-\_\.]+)@([a-zA-Z0-9\-\_]+)\.([a-zA-Z0-9]{2,4})',
				'message' => 'Votre adresse email n\'est pas valable',

			),

		),
	);

	public function checkPassword($login, $password){
		$user = $this->findFirst(array(
			'fields' => 'User.login, User.password, User.id, User.group_id, UserGroup.name as group_name, UserGroup.color as group_color, UserGroup.rights',
			'join' => array('groups as UserGroup' => 'User.group_id = UserGroup.id'),
			'conditions' => array('User.login' => $login),
		));
		if(!empty($user)){
			if(sha1($password) == $user->password){
				return $user;
			}else{
				$this->validateErrors['password'] = 'Le mot de passe ne semble pas être correct.';
			}
		}else{
			$this->validateErrors['login'] = 'Le compte ne semble pas exister.';
		}
		return false;
	}

	public function doConnection($user_id){
		$datas = array(
			'id'      => $user_id,
			'visited' => date("Y-m-d H:i:s",time()),
			'ip'      => $_SERVER['REMOTE_ADDR'],
		);

		$this->save($datas);
	}

	public function register($datas){
		$check = $this->checkSingle(array(
			'login' => $datas['login'],
			'email' => $datas['email'],
		), 'Register');
		if(empty($check)){
			$userDatas = array(
				'login'    => $datas['login'],
				'password' => sha1($datas['password']),
				'email'    => $datas['email'],
				'created'  => date("Y-m-d H:i:s",time()),
				'visited'  => date("Y-m-d H:i:s",time()),
				'ip'       => $_SERVER['REMOTE_ADDR'],
				'group_id' => 1,
			);
			$this->save($userDatas);
			$user = $this->findFirst(array(
				'fields' => 'User.login, User.password, User.id, User.group_id, UserGroup.name as group_name, UserGroup.color as group_color, UserGroup.rights',
				'join' => array('groups as UserGroup' => 'User.group_id = UserGroup.id'),
				'conditions' => array('User.id' => $this->id),
			));
			return $user;
		}
		return false;
	}

}

?>