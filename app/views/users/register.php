<?php 

$this->contentForHeader = '<div class="content_area"><h1>S\'inscrire</h1></div>';

$this->controller->loadPlugin('Form');

$this->controller->Form->create('User',null, 'POST', 'Register');

$this->controller->Form->separator('<br />');

$this->controller->Form->addField('login', array('label' => 'Votre login'));
$this->controller->Form->addField('password', array('label' => 'Votre mot de passe', 'type' => 'password'));
$this->controller->Form->addField('passwordcheck', array('label' => 'Votre mot de passe (vérification)', 'type' => 'password'));
$this->controller->Form->addField('email', array('label' => 'Votre adresse email'));

echo '<div class="cadre" style="text-align:center;">'.$this->controller->Form->build('Inscription !').'</div>';
?>