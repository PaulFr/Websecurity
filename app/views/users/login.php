<?php 

$this->contentForHeader = '<div class="content_area"><h1>S\'identifier</h1></div>';

$this->controller->loadPlugin('Form');

$this->controller->Form->create('User',null, 'POST', 'Login');

$this->controller->Form->separator('<br />');

$this->controller->Form->addField('login', array('label' => 'Votre login'));
$this->controller->Form->addField('password', array('label' => 'Votre mot de passe', 'type' => 'password'));

echo $this->controller->Form->build('Connexion !');
?>