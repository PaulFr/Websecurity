<?php 
  $this->controller->loadPlugin('Session');
?>
<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
    <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title><?php echo $this->titleForLayout; ?></title> 
    <link rel="stylesheet" href="<?php echo Router::wwwroot('css/style.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo Router::wwwroot('css/grille.min.css'); ?>" />
    </head> 
    <body>
      <div id="header">
        <div class="topbar">
          <div class="wrap">
            <a href="<?php echo Router::url(); ?>"><span class="logo"></span></a>
            <div class="menu">
              <ul>
                <?php if (isset($this->controller->Session->get('User')->login)): ?>
                <li class="btn"><a href="<?php echo Router::url('users/account'); ?>"><?php echo $this->controller->Session->get('User')->login; ?></a></li>
                <li class="btn"><a href="<?php echo Router::url('users/logout/'.$this->controller->Session->get('Token')); ?>">Se déconnecter</a></li>
                <?php else: ?>
                <li class="btn"><a href="<?php echo Router::url('users/login'); ?>">Se connecter</a></li>
                <li class="btn"><a href="<?php echo Router::url('users/register'); ?>">S'inscrire</a></li>
                <?php endif; ?>
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="sidebar">
          <div class="wrap">
            <ul>
              <li class="btn"><a href="<?php echo Router::url(); ?>">Accueil</a></li>
              <li class="btn"><a href="<?php echo Router::url('articles/index/tuto'); ?>">Tutos</a></li>
              <li class="btn"><a href="<?php echo Router::url('articles/index/article'); ?>">Blog</a></li>
              <li class="btn"><a href="<?php echo Router::url('works'); ?>">Travaux</a></li>
              <li class="btn"><a href="<?php echo Router::url('forum'); ?>">Forum</a></li>
              <li class="btn"><a href="<?php echo Router::url('contact'); ?>">Contact</a></li>
              <?php if (isset($this->controller->Session->get('User')->rights) && $this->controller->Session->get('User')->rights & Rights::get('ACCESS_ADMIN')): ?>
              <li class="btn"><a href="<?php echo Router::url('admin'); ?>">Admin</a></li>
              <?php endif ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="content_head">
        <div class="wrap">
        <span class="clearfix"></span>
          <?php
          echo $this->contentForHeader;
          echo $this->controller->Session->flash();
          ?>
        </div>
      </div>
      <div class="content content_area">
        <div class="wrap container_12">
            <?php echo $this->contentForLayout; ?>
        </div>
      </div>
      <div class="footer">
        <div class="wrap container_12">
          <div class="grid_4">
            <h1>En direct de twitter</h1>
            <?php
            $tweets = $this->controller->call('Twitter', 'last', array('Fawliet',3));
            foreach ($tweets->status as $k  => $v): ?>
              <p class="tweet"><?php echo $v->text; ?></p>
              <span class="hr"></span>
            <?php endforeach ?>
            <a href="https://twitter.com/Fawliet" class="twitter-follow-button" data-button="grey" data-text-color="#888888" data-link-color="#acacac" data-show-count="false" data-lang="fr">Suivre @Fawliet</a>
            <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
          </div>
          <div class="grid_4">
            <h1>Les News</h1>
            <?php 
            $news = $this->controller->call('Articles', 'last', array(3));
            ?>
            <?php foreach ($news as $k => $v): ?>
              <p class="tweet"><strong><?php echo strtoupper($v->type); ?></strong> : <a href="<?php echo Router::url('articles/see/'.$v->type.'/id:'.$v->id.'/slug:'.$v->slug); ?>"><?php echo $v->name; ?></a> le <?php echo $v->created; ?></p>
              <span class="hr"></span>
            <?php endforeach ?>
            <p><a href="<?php echo Router::url('articles/index/article'); ?>">Le blog</a>&nbsp; - &nbsp;<a href="<?php echo Router::url('articles/index/tuto'); ?>">Les tutoriels</a></p>
          </div>
          <div class="grid_4">
            <h1>Sur le web :</h1>
            <p>
              <a href="https://twitter.com/Fawliet"><img src="<?php echo Router::wwwroot('images/icons/twitter.png'); ?>" alt="twitter" class="icon"></a>&nbsp;
              <a href="https://plus.google.com/100282148189613899804/"><img src="<?php echo Router::wwwroot('images/icons/google.png'); ?>" alt="google+" class="icon"></a>&nbsp;
              <a href="http://facebook.com/"><img src="<?php echo Router::wwwroot('images/icons/facebook.png'); ?>" alt="facebook" class="icon"></a>&nbsp;
              <a href="https://lastfm.com"><img src="<?php echo Router::wwwroot('images/icons/lastfm.png'); ?>" alt="LastFM" class="icon"></a>&nbsp;
              <a href="https://youtube.com"><img src="<?php echo Router::wwwroot('images/icons/youtube.png'); ?>" alt="Youtube" class="icon"></a>&nbsp;
              <a href="https://gmail.com"><img src="<?php echo Router::wwwroot('images/icons/gmail.png'); ?>" alt="GMail" class="icon"></a>&nbsp;
            </p>
          </div>
        </div>
      </div>
    </body> 
    <script type="text/javascript">
        var PATH = "<?php echo URL; ?>/";
    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <?php echo $this->getJsFiles(); ?>

</html>
