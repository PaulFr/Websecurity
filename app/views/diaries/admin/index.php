<?php
	$this->contentForHeader = '<div class="content_area"><h1>Administration - Rapports</h1></div>';
?>
<div class="container_12">
	<div class="grid_8">
		<h1>Liste des rapports</h1>
		<div class="cadre">
			<table>
				<thead>
					<tr>
						<td>ID</td>
						<td>NOM</td>
						<td>AUTEUR</td>
						<td>PARTIES</td>
						<td>ACTIONS</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($diaries as $k => $v): ?>
					<tr>
						<td><?php echo $v->id; ?></td>
						<td><a href="<?php echo Router::url('diaries/read/hash:'.$v->uniqid.'/slug:'.$v->slug); ?>"><?php echo $v->name; ?></a></td>
						<td><?php echo $v->user_login; ?></td>
						<td><?php echo $v->nb_parties; ?></td>
						<td><a href="<?php echo Router::url('adm/diaries/details/'.$v->id); ?>">Détails</a> - <a href="<?php echo Router::url('adm/diaries/add/'.$v->id); ?>">Editer</a> - <a href="<?php echo Router::url('adm/diaries/delete/'.$v->id); ?>">Supprimer</a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<a href="<?php echo Router::url('adm/diaries/add'); ?>" class="button orange">Nouveau rapport</a>
	</div>
	<div class="grid_4">
		<h1>Actions</h1>
		<?php echo $this->controller->call('Admin', 'menu'); ?>
	</div>
</div>