<?php

class Article extends AppModel{
	
	public $validateRules = array(
		'add' => array(
			'name' => array(
				'rule'    => '(.+)',
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
			'category_id' => array(
				'rule' => '([0-9]+)',
				'message' => 'Catégorie incorrecte.',
			),
			'online' => array(
				'rule' => '^(0|1)$',
				'message' => 'Ce paramètre est un boolean...',
			),
		)
	);

}

?>