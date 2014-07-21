<?php

Router::prefix('ajax','ajax');
Router::prefix('adm','admin');


Router::connect('', 'articles');

Router::connect('adm','admin/index');

Router::connect('blog/:slug-:id.html', 'articles/see/article/id:([0-9]+)/slug:([a-z\-]+)');
Router::connect('tutoriels/:slug-:id.html', 'articles/see/tuto/id:([0-9]+)/slug:([a-z\-]+)');

Router::connect('blog/:page', 'articles/index/article/all/page:([0-9]+)');
Router::connect('tutoriels/:page', 'articles/index/tuto/all/page:([0-9]+)');

Router::connect('tutoriels/*', 'articles/index/tuto/*');
Router::connect('blog/*', 'articles/index/article/*');

Router::connect('search/:type', 'articles/search/type:([a-z]+)');

Router::connect('comment/:id', 'articles/comment/id:([0-9]+)');

Router::connect('rapports/:hash/:slug', 'diaries/read/hash:([a-z0-9]+)/slug:([a-z\-]+)');
Router::connect('rapports/*', 'diaries/*');

Router::connect('annuaire/fiche-identification-:name-:age.html', 'default/index/name:([a-zA-Z]+)/age:([0-9]+)');

?>