<?php

class Category extends AppModel{
	
	public function listCategories($type){
		return $this->find(array(
			'fields' => 'Category.id, Category.name, Category.slug, COUNT(Article.id) as nb_article',
			'join' => array('articles as Article' => 'Article.category_id = Category.id'),
			'group' => 'Category.id',
			'conditions' => array('Category.type' => $type),
		));
	}

}

?>