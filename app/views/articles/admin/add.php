<?php
	$this->contentForHeader = '<div class="content_area"><h1>'.($id ? 'Editer' : 'Ajouter').' un post</h1></div>';

$this->controller->loadPlugin('Form');

$this->controller->Form->create('Article',null, 'POST', 'add');

$this->controller->Form->separator('<br />');

$this->controller->Form->addField('name', array('label' => 'Nom du post'));
$this->controller->Form->addField('slug', array('label' => 'Slug du post'));
$this->controller->Form->addField('online', array('label' => 'En ligne ?', 'type' => 'checkbox'));
$this->controller->Form->addField('category_id', array('label' => 'Catégorie du post', 'type' => 'select', 'options' => $categories));
if($id)
	$this->controller->Form->addField('id', array('value' => (int) $id, 'type' => 'hidden'));
$this->controller->Form->addField('content', array('label' => 'Contenu', 'type' => 'textarea'));

echo '<div class="cadre" style="text-align:center;">'.$this->controller->Form->build('Go !').'</div>';