<?php

class Diary extends AppModel{
	public $validateRules = array(
		'add' => array(
			'name' => array(
				'rule'    => '([a-zA-Z0-9-_éèàêôùûëöäïîç\!\? ]+)',
				'message' => 'Le nom du rapport est incorrect.'
			),
			'slug' => array(
				'rule' => '([a-z0-9-]+)',
				'message' => 'Le slug ne doit être composé que de caractères alphanumériques et de tirets.'
			),
			'content' => array(
				'rule' => 'notEmpty',
				'message' => 'L\'introduction doit faire 3 caractères minimum.',
				'min' => 3,
			),
		)
	);
}

?>