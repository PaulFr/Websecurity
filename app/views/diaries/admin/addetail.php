<?php
	$this->contentForHeader = '<div class="content_area"><h1>'.($id ? 'Editer' : 'Ajouter').' un détail</h1></div>';

$this->controller->loadPlugin('Form');

$this->controller->Form->create('DiaryDetail',null, 'POST', 'add');

$this->controller->Form->separator('<br />');

$this->controller->Form->addField('name', array('label' => 'Nom du détail'));
$this->controller->Form->addField('critical', array('label' => 'Est-il critique ?', 'type' => 'checkbox'));
if($id)
	$this->controller->Form->addField('id', array('value' => (int) $id, 'type' => 'hidden'));
$this->controller->Form->addField('content', array('label' => 'Explications', 'type' => 'textarea'));

echo '<div class="cadre" style="text-align:center;">'.$this->controller->Form->build('Go !').'</div>';