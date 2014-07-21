<?php

class ArticlesController extends AppController{
	
	public $requiredModels = array('Article', 'Category');
	public $requiredPlugins = array('Session');

	public function index($type='article', $category='all', $page=1){

		$conditions = array('Article.type' => $type, 'Article.online' => 1);
		if($category != 'all')
			$conditions['Category.slug'] = $category;

		if($page < 1) $page = 1;

		$articlesPerPage = 2;
		$nbArticles = $this->Article->findCount($conditions, array('join' => array(
				'categories as Category' => 'Article.category_id = Category.id',
		)));
		if($nbArticles <= 0)
			Core::throwError(404, 'Cette catégorie est inexistante ou vide !');
		$nbPages = ceil($nbArticles/$articlesPerPage);

		if($page > $nbPages) $page = $nbPages;
		$startAt = (intval($page)-1)*$articlesPerPage;

		$this->view->bind(array(
			'currentPage' => $page,
			'nbPages' => $nbPages,
			'type' => htmlspecialchars($type),
			'currentCategory' => $category,
			'categories' => $this->Category->listCategories($type),
		));

		$v['req'] = $this->Article->find(array(
			'fields' => 'Article.id, Article.name, Article.content, DATE_FORMAT(Article.created, \'%d/%m/%Y\') as created, Article.slug, User.id as user_id, User.login, Category.id as category_id, Category.name as category_name, Category.slug as category_slug, COUNT(Comment.id) as nb_comment',
			'join' => array(
				'users as User' => 'Article.user_id = User.id',
				'categories as Category' => 'Article.category_id = Category.id',
				'comments as Comment' => 'Article.id = Comment.article_id',
			),
			'conditions' => $conditions, 
			'group' => 'Article.id',
			'order' => 'Article.created DESC',
			'limit' => $startAt.','.$articlesPerPage,
		));
		$this->view->bind($v);
	}

	public function call_last($nb){
		return $this->Article->find(array(
			'fields' => 'Article.id, Article.name, Article.type, DATE_FORMAT(Article.created, \'%d/%m/%Y à %Hh%i\') as created, Article.slug',
			'limit' => $nb,
		));
	}

	public function comment($id){
		$this->loadModel('Comment');
		$article = $this->Article->findFirst(array(
			'fields' => 'Article.id, Article.name, Article.slug, Article.type',
			'conditions' => array('Article.online' => 1, 'Article.id' => $id),                                                                                                               
		));
		if(!$article)
			Core::throwError(404); 
		if(isset($this->request->datas['Comment'])){
			if(!isset($this->Session->get('User')->login)){

				$this->Comment->validate($this->request->datas['Comment'], 'CommentDisconnected');
				
				if(empty($this->Comment->validateErrors['CommentDisconnected'])){
					$datas = array(
						'article_id' => $article->id,
						'user_id' => 0,
						'content' => nl2br(htmlspecialchars($this->request->datas['Comment']['content'])),
						'created' => date("Y-m-d H:i:s",time()),
						'ip' => $_SERVER['REMOTE_ADDR'],
						'pseudo' => $this->request->datas['Comment']['pseudo'],
						'email' => $this->request->datas['Comment']['email'],
					);
				}
			}else{
				$this->Comment->validate($this->request->datas['Comment'], 'CommentConnected');
				if(empty($this->Comment->validateErrors['CommentConnected'])){
					$datas = array(
						'article_id' => $article->id,
						'user_id' => $this->Session->get('User')->id,
						'content' => nl2br(htmlspecialchars($this->request->datas['Comment']['content'])),
						'created' => date("Y-m-d H:i:s",time()),
						'ip' => $_SERVER['REMOTE_ADDR'],
					);
				}
			}
		}
		if(isset($datas)){
			$this->Comment->save($datas);
			$this->Session->setFlash('Votre commentaire a bien été posté.');
			$this->response->redirect('articles/see/'.$article->type.'/id:'.$article->id.'/slug:'.$article->slug);
		}
		$this->view->bind('article', $article);
	}

	public function see($type='article', $id, $slug=''){

		$this->view->bind(array(
			'type' => htmlspecialchars($type),
			'categories' => $this->Category->listCategories($type),
		));

		$v['article'] = $this->Article->findFirst(array(
			'fields' => 'Article.id, Article.name, Article.content, Article.modified, DATE_FORMAT(Article.created, \'%d/%m/%Y\') as created, Article.slug, Article.type, User.id as user_id, User.login, Category.id as category_id, Category.name as category_name, Category.slug as category_slug',
			'join' => array(
				'users as User' => 'Article.user_id = User.id',
				'categories as Category' => 'Article.category_id = Category.id',
			),
			'conditions' => array('Article.type' => $type, 'Article.online' => 1, 'Article.id' => $id),                                                                                                               
		));
		if(!$v['article']){ 
			Core::throwError(404); 
		}
		if($v['article']->slug != $slug) $this->response->redirect('articles/see/'.$type.'/id:'.$v['article']->id.'/slug:'.$v['article']->slug, true);
		$this->loadModel('Comment');
		$v['comments'] = $this->Comment->getAll($v['article']->id);
		$this->view->bind($v);
	}

	public function search($type){
		$this->view->bind(array(
			/*'currentPage' => $page,
			'nbPages' => $nbPages,*/
			'type' => htmlspecialchars($type),
			/*'currentCategory' => $category,
			'categories' => $this->Category->listCategories($type),*/
		));
		if(!empty($this->request->datas['Search']['query'])){
			$v['result'] = $this->Article->find(array(

				'fields' => 'Article.id, Article.name, Article.content, Article.modified, Article.created, Article.slug, User.id as user_id, User.login, Category.id as category_id, Category.name as category_name, Category.slug as category_slug, COUNT(Comment.id) as nb_comment, MATCH (Article.name,Article.content) AGAINST ("'.mysql_escape_string($this->request->datas['Search']['query']).'") as score',
				'join' => array(
					'users as User' => 'Article.user_id = User.id',
					'categories as Category' => 'Article.category_id = Category.id',
					'comments as Comment' => 'Article.id = Comment.article_id',
				),
				'conditions' => array('Article.type' => $type, 'Article.online' => 1),
				'order' => 'score DESC',
				'group' => 'Article.id',

			));

			$this->view->bind($v);
		}

	}

	//ADMIN

	public function admin_index($type){
		$this->view->bind(array('type' => htmlspecialchars($type)));
		$v['articles'] = $this->Article->find(array(
			'fields' => 'Article.id, Article.name, Article.content, DATE_FORMAT(Article.created, \'%d/%m/%Y\') as created, Article.online, Article.slug, User.id as user_id, User.login as user_login, Category.id as category_id, Category.name as category_name, Category.slug as category_slug, COUNT(Comment.id) as nb_comment',
			'join' => array(
				'users as User' => 'Article.user_id = User.id',
				'categories as Category' => 'Article.category_id = Category.id',
				'comments as Comment' => 'Article.id = Comment.article_id',
			),
			'conditions' => array('Article.type' => $type), 
			'group' => 'Article.id',
			'order' => 'Article.created DESC',
		));
		$this->view->bind($v);
	}

	public function admin_add($target){
		if(is_numeric($target)){
			$article = $this->Article->findFirst(array(
				'fields' => 'Article.id, Article.name, Article.content, Article.modified, DATE_FORMAT(Article.created, \'%d/%m/%Y\') as created, Article.slug, Article.type',
				'conditions' => array('Article.id' => $target),                                                                                                               
			));
			if(!$article){ 
				Core::throwError(404); 
			}
			$this->Article->prefill($target);
			$categories = $this->Category->listCategories($article->type);
		}else{
			$categories = $this->Category->listCategories($target);
		}

		if(isset($this->request->datas['Article'])){
			$this->Article->validate($this->request->datas['Article'], 'add');
			if(empty($this->Article->validateErrors['add'])){
				if(is_numeric($target)){
					$datas = array(
						'name' => $this->request->datas['Article']['name'],
						'slug' => $this->request->datas['Article']['slug'],
						'category_id' => $this->request->datas['Article']['category_id'],
						'online' => $this->request->datas['Article']['online'],
						'content' => $this->request->datas['Article']['content'],
						'modified' => date("Y-m-d H:i:s",time()),
						'id' => $target,
					);
					$retour = $article->type;
				}else{
					$datas = array(
						'name' => $this->request->datas['Article']['name'],
						'slug' => $this->request->datas['Article']['slug'],
						'category_id' => $this->request->datas['Article']['category_id'],
						'online' => $this->request->datas['Article']['online'],
						'content' => $this->request->datas['Article']['content'],
						'created' => date("Y-m-d H:i:s",time()),
						'user_id' => $this->Session->get('User')->id,
						'type' => $target,
					);
					$retour = $target;
				}
				$this->Article->save($datas);
				$this->Session->setFlash('Votre action a bien été prise en compte.');
				$this->response->redirect('adm/articles/index/'.$retour);
			}
		}

		$d['categories'] = array();
		foreach ($categories as $v) {
			$d['categories'][$v->id] = $v->name;
		}
		$d['id'] = (int) $target;
		$this->view->bind($d);
	}

	public function admin_delete($id){
		$this->loadModel('Comment');
		$article = $this->Article->findFirst(array(
			'fields' => 'Article.id, Article.name, Article.content, Article.modified, DATE_FORMAT(Article.created, \'%d/%m/%Y\') as created, Article.slug, Article.type',
			'conditions' => array('Article.id' => $id),                                                                                                               
		));
		if(!$article){
			Core::throwError(404);
		}
		$type = $article->type;
		$this->Article->delete($id);
		$comments = $this->Comment->find(array(
			'fields' => 'Comment.id',
			'conditions' => array('Comment.article_id' => $id),
		));
		foreach ($comments as $v) {
			$this->Comment->delete($v->id);
		}
		$this->Session->setFlash('Le post a bien été supprimé.');
		$this->response->redirect('adm/articles/index/'.$type);
	}

}

?>