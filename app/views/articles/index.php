<?php
$this->controller->loadPlugin('form');
$this->controller->Form->create('Search', Router::url('articles/search/type:'.$type), 'POST');
$this->controller->Form->addField('query', array('label' => 'Recherche'));
$this->contentForHeader = '<div class="content_area container_12"><h1 class="grid_7">Les '.$type.'s</h1><div class="grid_5">'.$this->controller->Form->build('Chercher !').'</div></div>';
?>

<div id="container_12">
	<div class="grid_9">
		<?php foreach ($req as $k => $v): ?>
		<div class="post">
			<?php
			$v->content = previewContent($v->content,1000);
			?>
			<h1><a href="<?php echo Router::url('articles/see/'.$type.'/id:'.$v->id.'/slug:'.$v->slug); ?>"><?php echo $v->name; ?></a></h1>
			<h2>Le <?php echo $v->created; ?> par <?php echo $v->login; ?> dans <a href="<?php echo Router::url('articles/index/'.$type.'/'.$v->category_slug); ?>"><?php echo $v->category_name; ?></a> <a href="<?php echo Router::url('articles/see/'.$type.'/id:'.$v->id.'/slug:'.$v->slug); ?>#comments">(<?php echo $v->nb_comment; ?> commentaire<?php echo $v->nb_comment > 1 ? 's' : ''; ?>)</a></h2>
			<p class="preview"><?php echo nl2br($v->content); ?></p>
			<span class="hr"></span>
		</div>
		<?php endforeach; ?>

		<div class="pagination">
				<span>Pages :</span>
				<?php for($i=0; $i < $nbPages; $i++): ?>
					<?php if ($currentPage == $i+1): ?>
						<p class="hover"><?php echo $i+1; ?></p>
					<?php else: ?>
						<p class="hoverizable"><a href="<?php echo Router::url('articles/index/'.$type.'/'.($currentCategory != 'all' ? $currentCategory.'/' : '').($i+1)); ?>"><?php echo $i+1; ?></a></p>
					<?php endif ?>
				<?php endfor; ?>
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
