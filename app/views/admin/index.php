<?php
	$this->contentForHeader = '<div class="content_area"><h1>Administration</h1></div>';
?>
<div class="container_12">
	<div class="grid_8">
		<h1>Bienvenue dans l'administration</h1>
		<div class="cadre">
			<p>Bienvenue dans l'accueil de l'administration. Vous pouvez choisir d'effectuer une action grâce au menu de droite.</p>
		</div>
	</div>
	<div class="grid_4">
		<h1>Actions</h1>
		<?php echo $this->controller->call('Admin', 'menu'); ?>
	</div>
</div>