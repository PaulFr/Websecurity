<?php 
	$this->contentForHeader = '<div class="content_area"><h1>Visionner un rapport</h1></div>';
	$this->addJsFile(Router::wwwroot('js/lib_diaries.min.js'));
	$this->addJsFile(Router::wwwroot('js/scrollTo.js'));
	$nbCorrected = 0;
?>
<div id="container_12">
	<div class="grid_8">
		<h1 class="bigger"><?php echo $diary->name; ?></h1>
		<div class="post">
			<p><?php echo nl2br($diary->content); ?></p>
			<span class="hr"></span>
			<?php foreach ($details as $k => $v): ?>
			<div class="detail cadre" id="detail<?php echo $v->id; ?>">
				<?php if (!empty($v->critical)): ?>
					<span class="mention rouge">Critique</span>
				<?php endif ?>
				<?php if (!empty($v->corrected_date) && ($v->corrected_date) != '00/00/0000 à 00h00'): $nbCorrected++; ?>
				<span class="mention vert" id="c<?php echo $v->id; ?>">Corrigé (<?php echo $v->corrected_date; ?>)</span>
				<span class="mention noir"><a href="#" id="<?php echo $v->id; ?>-<?php echo $diary->uniqid; ?>-0" class="diaryState"><img src="<?php echo Router::wwwroot('images/icons/cancel.png'); ?>" alt="Annuler"></a></span>
				<?php else: ?>
				<span class="mention noir"><a href="#" id="<?php echo $v->id; ?>-<?php echo $diary->uniqid; ?>-1" class="diaryState"><img src="<?php echo Router::wwwroot('images/icons/valid.png'); ?>" alt="Corriger"></a></span>
				<?php endif ?>
				<h3><a name="<?php echo $v->id; ?>" class="autoScroll" id="<?php echo $v->id; ?>" href="#<?php echo $v->id; ?>"><?php echo $v->name; ?></a></h3>
				<h2>Ajouté le <?php echo $v->created; ?></h2>
				<p><?php echo nl2br($v->content); ?></p>
			</div>
			<span class="hr"></span>
			<?php endforeach ?>
		</div>
	</div>
	<div class="grid_4">
		<h1>Informations</h1>
		<div class="cadre">
			<p>Auteur du rapport : <a href=""><?php echo $diary->login; ?></a></p>
			<p>Date de création : <?php echo $diary->created; ?></p>
			<?php if (!empty($diary->modified)): ?>
			<p>Dernière modification le : <?php echo $diary->modified; ?></p>
			<?php endif ?>
			<p>Nombre de sous-parties : <?php echo count($details); ?></p>
			<?php if (count($details) > 0): ?>
			<div id="percent">
			<p>Correction : <span><?php echo round(($nbCorrected/count($details))*100); ?>%</span></p>
			<p class="percent_bar"><span class="current" style="width:<?php echo round(($nbCorrected/count($details))*100); ?>%;"></span></p>
			</div>
			<?php endif ?>
		</div>
		<h1>Plan du rapport</h1>
		<div class="cadre">
			<ul>
		<?php if (count($details) <= 0): ?>
				<li>Aucun pour le moment.</li>
		<?php endif ?>
		<?php foreach ($details as $k => $v): ?>
				<li><a class="autoScroll" href="#<?php echo $v->id; ?>"><?php echo $v->name; ?></a></li>
		<?php endforeach ?>
			</ul>
		</div>
		<h1>Aide</h1>
		<div class="cadre">
			<p>Cette page permet de lister les vulnérabilités ayant été trouvées sur votre site. Vous avez la possibilité de marquer une vulnérabilité comme corrigée. Vous pouvez aussi annuler cette action. Une fois toutes les failles corrigées, ce rapport devient publique pour permettre à d'autres internautes de ne pas reproduire les mêmes erreurs en voyant ce qui peut clocher dans leur façon de faire.<br /><br /></p>
			<p><strong>Attention : </strong> Passé un délais de deux mois, cette page deviendra automatiquement publique même si toutes les sous-parties ne sont pas marquées comme corrigées.</p>
		</div>
	</div>
</div>