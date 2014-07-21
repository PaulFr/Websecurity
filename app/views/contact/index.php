<?php
$this->contentForHeader = '<div class="content_area"><h1>Me contacter</h1></div>';
$this->controller->loadPlugin('Form');
$this->controller->Form->create('Contact', null, 'POST');
$this->controller->Form->separator('<br />');
$this->controller->Form->addField('name', array('label' => 'Votre nom'));
$this->controller->Form->addField('email', array('label' => 'Votre adresse e-mail'));
$this->controller->Form->addField('message', array('label' => 'Votre message', 'type' => 'textarea'));
?>

<div class="container_12">
	<div class="grid_10">
		<h1>Par email</h1>
		<?php echo $this->controller->Form->build('Valider !'); ?>
	</div>
	<div class="grid_2">
		<h1>Sur le web</h1>
		<div class="space"></div>
			<div class="social">
				<p><a href="https://twitter.com/Fawliet"><img src="<?php echo Router::wwwroot('images/icons/twitter.png'); ?>" alt="twitter" class="icon"></a></p>
				<p><a href="https://plus.google.com/100282148189613899804/"><img src="<?php echo Router::wwwroot('images/icons/google.png'); ?>" alt="google+" class="icon"></a></p>
				<p><a href="http://facebook.com/"><img src="<?php echo Router::wwwroot('images/icons/facebook.png'); ?>" alt="facebook" class="icon"></a></p>
				<p><a href="https://lastfm.com"><img src="<?php echo Router::wwwroot('images/icons/lastfm.png'); ?>" alt="LastFM" class="icon"></a></p>
				<p><a href="https://youtube.com"><img src="<?php echo Router::wwwroot('images/icons/youtube.png'); ?>" alt="Youtube" class="icon"></a></p>
				<p><a href="https://gmail.com"><img src="<?php echo Router::wwwroot('images/icons/gmail.png'); ?>" alt="GMail" class="icon"></a></p>
			</div>
	</div>
</div>																															