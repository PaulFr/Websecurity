<?php
$this->contentForHeader = '<div class="content_area"><h1>Oops !</h1></div>';
?>
 <h1>Une erreur est survenue !</h1>
 <p><?php echo $message; ?></p>
 <p><a href="<?php echo Router::url(); ?>" class="btn primary large">Retourner à l'accueil.</a></p>
