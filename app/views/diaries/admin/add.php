<?php
	$this->contentForHeader = '<div class="content_area"><h1>'.($id ? 'Editer' : 'Ajouter').' un rapport</h1></div>';

$this->controller->loadPlugin('Form');

$this->controller->Form->create('Diary',null, 'POST', 'add');

$this->controller->Form->separator('<br />');

$this->controller->Form->addField('name', array('label' => 'Nom du rapport'));
$this->controller->Form->addField('slug', array('label' => 'Slug du rapport'));
if($id)
	$this->controller->Form->addField('id', array('value' => (int) $id, 'type' => 'hidden'));
$this->controller->Form->addField('content', array('label' => 'Introduction', 'type' => 'textarea'));

echo '<div class="cadre" style="text-align:center;">'.$this->controller->Form->build('Go !').'</div>';