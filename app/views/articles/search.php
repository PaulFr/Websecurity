<?php
$this->contentForHeader = '<div class="content_area"><h1>Résultats de la recherche</h1></div>';
if(isset($result)){
	?>
<div id="container_12">
	<div class="grid_12">
		<?php 
		$total = 0;

		foreach ($result as $k => $v): if($v->score <= 0){break;} $total++;?>
		<div class="post">
			<?php
			$v->content = previewContent($v->content,1000);
			?>
			<h1><a href="<?php echo Router::url('articles/see/'.$type.'/id:'.$v->id.'/slug:'.$v->slug); ?>"><?php echo $v->name; ?></a></h1>
			<h2>Le <?php echo $v->created; ?> par <?php echo $v->login; ?> dans <a href="<?php echo Router::url('articles/index/'.$type.'/'.$v->category_slug); ?>"><?php echo $v->category_name; ?></a> <a href="<?php echo Router::url('articles/see/'.$type.'/id:'.$v->id.'/slug:'.$v->slug); ?>">(<?php echo $v->nb_comment; ?> commentaire<?php echo $v->nb_comment > 1 ? 's' : ''; ?>)</a></h2>
			<p class="preview"><?php echo $v->content; ?></p>
			<h1 style="float:right">Correspondance : <?php echo round($v->score*100); ?>%</h1>
			<span class="hr"></span>
		</div>
		<?php endforeach; ?>
		<?php if ($total <= 0): ?>
			<p>Il n'y a aucun résultat pour les mots clés que vous avez tapé.</p>
		<?php endif ?>
	
	<?php
}else{
	echo '<p>Vous n\'avez pas tapé de mot clé, quel intérêt ??</p>';
}
?>
	</div>
</div>