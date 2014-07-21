<?php 
$this->controller->loadPlugin('form');
$this->controller->Form->create('Search', Router::url('articles/search/type:'.$type), 'POST');
$this->controller->Form->addField('query', array('label' => 'Recherche'));
$this->contentForHeader = '<div class="content_area container_12"><h1 class="grid_7">Les '.$type.'s</h1><div class="grid_5">'.$this->controller->Form->build('Chercher !').'</div></div>';
 ?>

<div id="container_12">
	<div class="grid_9">
		<div class="post">
			<h1><?php echo $article->name; ?></h1>
			<h2>Le <?php echo $article->created; ?> par <?php echo $article->login; ?> dans <a href="<?php echo Router::url('articles/index/'.$type.'/'.$article->category_slug); ?>"><?php echo $article->category_name; ?></a></h2>
			<p><?php echo nl2br($article->content); ?></p>
			<span class="hr"></span>
			<a href="" name="comments"></a>
			<?php if (count($comments) <= 0): ?>
				<p>Il n'y a pas encore de commentaire. Vous pouvez être le premier à poster !</p>
			<?php endif ?>
			<?php foreach ($comments as $k => $v): ?>
				<div class="comment">
					<?php 
						$pseudo = $v->user_id > 0 ? $v->user_login : $v->pseudo;
						$email = $v->user_id > 0 ? $v->user_email : $v->email;
						$content = $v->content;
					?>

					<div class="avatar"><img src="http://www.gravatar.com/avatar/<?php echo md5($email); ?>?s=50" alt=""></div>
					<div class="contentcomment">
						<div><span class="title"><?php echo $pseudo; ?></span> <span class="min">- <?php echo $v->created; ?></span></div>
						<p><?php echo $v->content; ?></p>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endforeach ?>
			<span class="hr"></span>
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
		</div>

	</div>
	<div class="grid_3">
		<h1>Catégories</h1>
		<ul>
			<?php foreach ($categories as $k => $v): ?>
				<?php if ($v->nb_article>0): ?>
					<li><a href="<?php echo Router::url('articles/index/'.$type.'/'.$v->slug); ?>"><?php echo $v->name; ?></a> (<?php echo $v->nb_article; ?>)</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</div>
</div>