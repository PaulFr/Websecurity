<?php

$config['development'] = array(
	'SITE_NAME'      => 'WebSecurity (LOCAL)',
	'DEFAULT_LAYOUT' => 'default',
	'DEBUG_MODE'     => 1,
	'PREFIX_PRIMARY_KEY' => false,
	'DATABASES'      => array(
		'default' => array(
			'host'          => 'localhost',
			'user'          => 'root',
			'password'      => '',
			'database' => 'websecurity',
			'prefix'        => '',
		),
	),
);
$config['production'] = array(
	'SITE_NAME'      => 'WebSecurity',
	'DEFAULT_LAYOUT' => 'default',
	'DEBUG_MODE'     => 0,
	'PREFIX_PRIMARY_KEY' => false,
	'DATABASES'      => array(
		'default' => array(
			'host'          => 'mysql.xxx.com',
			'user'          => 'websecurity',
			'password'      => 'xxx',
			'database' => 'websecurity_database',
			'prefix'        => '',
		),
	),
);

$config = isset($config[MODE]) ? $config[MODE] : $config['development'];

class Config{
	private static $config;

	public static function setConfig($conf){
		self::$config = $conf;
	}

	public static function get($key){
		return self::$config[$key];
	}
}

Config::setConfig($config);
unset($config);
?>