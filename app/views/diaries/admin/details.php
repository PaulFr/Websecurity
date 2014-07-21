<?php
	$this->contentForHeader = '<div class="content_area"><h1>Administration - Rapports &gt; Détails</h1></div>';
?>
<div class="container_12">
	<div class="grid_8">
		<h1>Liste des détails de "<?php echo $diary->name; ?>"</h1>
		<div class="cadre">
			<table>
				<thead>
					<tr>
						<td>ID</td>
						<td>NOM</td>
						<td>CRITIQUE</td>
						<td>CORRIGE</td>
						<td>ACTIONS</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($details as $k => $v): ?>
					<tr>
						<td><?php echo $v->id; ?></td>
						<td><a href="<?php echo Router::url('diaries/read/hash:'.$diary->uniqid.'/slug:'.$diary->slug); ?>#<?php echo $v->id; ?>"><?php echo $v->name; ?></a></td>
						<td><?php echo $v->critical ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $v->corrected_date != '00/00/0000 à 00h00' && !empty($v->corrected_date) ? 'Oui' : 'Non'; ?></td>
						<td><a href="<?php echo Router::url('adm/diaries/addetail/'.$diary->id.'/'.$v->id); ?>">Editer</a> - <a href="<?php echo Router::url('adm/diaries/deletedetail/'.$v->id); ?>">Supprimer</a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<a href="<?php echo Router::url('adm/diaries/addetail/'.$diary->id); ?>" class="button orange">Nouveau détail</a>
	</div>
	<div class="grid_4">
		<h1>Actions</h1>
		<?php echo $this->controller->call('Admin', 'menu'); ?>
	</div>
</div>