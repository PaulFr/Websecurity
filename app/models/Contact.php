<?php

class Contact extends AppModel{
	public $validateRules = array(
		'Contact' => array(
			'name' => array(
				'rule'    => '([a-zA-Z0-9\-\_]+)',
				'message' => 'Votre nom est incorrect.'
			),
			'email' => array(
				'rule' => '([a-zA-Z0-9\-\_\.]+)@([a-zA-Z0-9\-\_]+)\.([a-zA-Z0-9]+)',
				'message' => 'Votre adresse email n\'est pas valide.'
			),
			'message' => array(
				'rule' => 'notEmpty',
				'message' => 'Votre message ne doit pas être vide (3 caractères minimum).',
				'min' => 3,
			),
		)
	);
}

?>