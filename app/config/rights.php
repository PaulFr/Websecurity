<?php
	class Rights{
		public static $rigths = array(
			'ACCESS_ADMIN'     => array(
				'value' => 0x01,
				'desc' => 'Permet d\'acceder à l\'administration.',
				'name' => 'Accès à l\'administration',
				'admin' => false,
			),
			'MANAGE_DIARIES'     => array(
				'value' => 0x02,
				'desc' => 'Permet de gérer les rapports.',
				'name' => 'Gérer les rapports',
				'url' => 'adm/diaries',
			),
			'MANAGE_POSTS'      => array(
				'value' => 0x04,
				'desc' => 'Permet de gérer les articles du blog.',
				'name' => 'Gérer le blog',
				'url' => 'adm/articles/index/article',
			),
			'MANAGE_TUTORIELS' => array(
				'value' => 0x08,
				'desc' => 'Permet de gérer les tutoriels.',
				'name' => 'Gérer les tutoriels',
				'url' => 'adm/articles/index/tuto',
			),
			'MANAGE_COMMENTS'  => array(
				'value' => 0x10,
				'desc' => 'Permet de gérer les commentaires',
				'name' => 'Gérer les commentaires',
				'admin' => false,
			),
			'MANAGE_WORKS'     => array(
				'value' => 0x20,
				'desc' => 'Permet de gérer les travaux.',
				'name' => 'Gérer les travaux',
				'url' => 'adm/works',
			),
			'MANAGE_FORUM'     => array(
				'value' => 0x40,
				'desc' => 'Permet de gérer les forums.',
				'name' => 'Gérer le forum',
				'url' => 'adm/forum',
			),
			'MODERATE_FORUM'   => array(
				'value' => 0x80,
				'desc' => 'Permet de modérer le forum.',
				'name' => 'Gérer les contenus du forum.',
				'admin' => false,
			),
			'MANAGE_GROUPS'    => array(
				'value' => 0x100,
				'desc' => 'Permet de gérer les groupes utilisateurs.',
				'name' => 'Gérer les groupes',
				'url' => 'adm/groups',
			),
			'MANAGE_USERS'     => array(
				'value' => 0x200,
				'desc' => 'Permet de gérer les utilisateurs',
				'name' => 'Gérer les utilisateurs',
				'url' => 'adm/users',
			),
		);

		public static function get($key = null){
			if($key != null){
				return self::$rigths[$key]['value'];
			}else{
				return self::$rigths;
			}
		}

	}
?>