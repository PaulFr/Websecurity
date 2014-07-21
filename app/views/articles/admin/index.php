<?php
	$this->contentForHeader = '<div class="content_area"><h1>Administration - '.ucfirst($type).'s</h1></div>';
?>
<div class="container_12">
	<div class="grid_8">
		<h1>Liste des <?php echo ($type); ?>s</h1>
		<div class="cadre">
			<table>
				<thead>
					<tr>
						<td>ID</td>
						<td>NOM</td>
						<td>AUTEUR</td>
						<td>CATEGORIE</td>
						<td>EN LIGNE</td>
						<td>ACTIONS</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($articles as $k => $v): ?>
					<tr>
						<td><?php echo $v->id; ?></td>
						<td><?php echo $v->name; ?></td>
						<td><?php echo $v->user_login; ?></td>
						<td><?php echo $v->category_name; ?></td>
						<td><?php echo $v->online ? 'Oui' : 'Non'; ?></td>
						<td><a href="<?php echo Router::url('adm/articles/add/'.$v->id); ?>">Editer</a> - <a href="<?php echo Router::url('adm/articles/delete/'.$v->id); ?>">Supprimer</a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>	
		<a href="<?php echo Router::url('adm/articles/add/'.$type); ?>" class="button orange">Nouveau</a>																									
	</div>
	<div class="grid_4">
		<h1>Actions</h1>
		<?php echo $this->controller->call('Admin', 'menu'); ?>
	</div>
</div>