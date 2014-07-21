<?php

class DiaryDetail extends AppModel{
	
	public $validateRules = array(
		'add' => array(
			'name' => array(
				'rule'    => '([a-zA-Z0-9-_éèàêôùûëöäïîç\!\? ]+)',
				'message' => 'Le nom du détail est incorrect.'
			),
			'critical' => array(
				'rule' => '^(0|1)$',
				'message' => 'Ce champ ne peut être que vrai ou faux...'
			),
			'content' => array(
				'rule' => 'notEmpty',
				'message' => 'L\'explication doit faire 3 caractères minimum.',
				'min' => 3,
			),
		)
	);

}

?>