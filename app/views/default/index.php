
<p>Salut <?php echo $pseudo; ?>, tu as <?php echo $age; ?> ans et je test ! <a href="<?php echo Router::url('default/index/name:ThatYouWant/age:87'); ?>">test</a></p>

<?php 
$this->controller->loadPlugin('form');


$this->controller->Form->create('blog',$this->controller, null, 'POST');
$this->controller->Form->addField('avatar', array(
	'label' => 'Votre avatar',
	'type' => 'file',
));
$this->controller->Form->addField('pseudo', array(
	'label' => 'Votre pseudo',
	'type' => 'text',
));
$this->controller->Form->addField('pass', array(
	'label' => 'Votre mot de passe',
	'type' => 'password',
));
$this->controller->Form->addField('grade', array(
	'label' => 'Votre grade',
	'type' => 'select',
	'options' => array(
		'modo' => 'Modérateur',
		'admin' => 'Administrateur',
		'redac' => 'Rédacteur',
		'consultant' => 'Consultant',
	),
));
$this->controller->Form->addField('sex', array(
	'label' => 'Votre sexe',
	'type' => 'radio',
	'options' => array(
		'girl' => 'Fille',
		'boy' => 'Garçon',
		'unknown' => 'Inconnu',
	),
));
echo $this->controller->Form->build('Inscription !');


?>
