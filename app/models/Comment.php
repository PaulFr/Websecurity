<?php

class Comment extends AppModel{
	
	public $validateRules = array(
		'CommentDisconnected' => array(
			'pseudo' => array(
				'rule' => '^([a-zA-Z0-9-_]{3,})$',
				'message' => 'Votre pseudo doit contenir au minimum 3 caractères.',
			),
			'content' => array(
				'rule' => 'notEmpty',
				'message' => 'Votre message doit faire 3 caractères au minimum.',
				'min' => 3,
			),
			'email' => array(

				'rule' => '([a-zA-Z0-9\-\_\.]+)@([a-zA-Z0-9\-\_]+)\.([a-zA-Z0-9]{2,4})',
				'message' => 'Votre adresse email n\'est pas valide.',

			),
		),
		'CommentConnected' => array(

			'content' => array(
				'rule' => 'notEmpty',
				'message' => 'Votre message doit faire 3 caractères au minimum.',
				'min' => 3,
			),

		),
	);

	public function getAll($article_id){
		
		return $this->find(array(

			'fields' => 'Comment.id, Comment.user_id, Comment.content, DATE_FORMAT(Comment.created, \'%d/%m/%Y à %Hh%i\') as created, Comment.ip, Comment.email, Comment.pseudo, User.login as user_login, User.email as user_email, UserGroup.color as color_login',
			'join' => array('users as User' => 'Comment.user_id = User.id', 'groups as UserGroup' => 'User.group_id = UserGroup.id'),
			'conditions' => array('Comment.article_id' => $article_id),
			'group' => 'Comment.id',
			'order' => 'Comment.created ASC',

		));

	}

}

?>