<?php 
$this->controller->loadPlugin('form');
$this->contentForHeader = '<div class="content_area"><h1>Poster un commentaire</h1></div>';
 ?>

<h1><?php echo $article->name; ?></h1>
<div class="cadre">
<?php 
$this->controller->loadPlugin('form');
if(!isset($this->controller->Session->get('User')->login)){
	$this->controller->Form->create('Comment', Router::url('articles/comment/id:'.$article->id), 'POST', 'CommentDisconnected');
	$this->controller->Form->addField('pseudo', array('label' => 'Votre pseudo'));
	$this->controller->Form->addField('email', array('label' => 'Votre adresse email'));
}else{
	$this->controller->Form->create('Comment', Router::url('articles/comment/id:'.$article->id), 'POST', 'CommentConnected');
}
$this->controller->Form->addField('content', array('label' => 'Votre message', 'type' => 'textarea'));
echo $this->controller->Form->build('Commenter !');
			?>
</div>

