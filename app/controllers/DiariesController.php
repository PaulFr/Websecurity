<?php

class DiariesController extends AppController{
	
	public $requiredModels = array('Diary', 'DiaryDetail');
	public $requiredPlugins = array('Session');

	public function index(){
		
	}

	public function read($hash, $slug){
		$d['diary'] = $this->Diary->findFirst(array(
			'fields' => 'Diary.id, Diary.name, Diary.content, Diary.slug, Diary.uniqid, DATE_FORMAT(Diary.created, \'%d/%m/%Y à %Hh%i\') as created, DATE_FORMAT(Diary.modified, \'%d/%m/%Y à %Hh%i\') as modified, User.id as user_id, User.login',
			'join' => array(
				'users as User' => 'Diary.user_id = User.id',
			),
			'conditions' => array('Diary.uniqid' => $hash),
		));
		if(!$d['diary']) Core::throwError(404);
		if($d['diary']->slug != $slug) $this->response->redirect('diaries/read/hash:'.$d['diary']->uniqid.'/slug:'.$d['diary']->slug, true);

		$this->loadModel('DiaryDetail');
		$d['details'] = $this->DiaryDetail->find(
			array(
				'fields' => 'DiaryDetail.id, DiaryDetail.name, DiaryDetail.content, DiaryDetail.critical, DATE_FORMAT(DiaryDetail.created, \'%d/%m/%Y à %Hh%i\') as created, DATE_FORMAT(DiaryDetail.corrected_date, \'%d/%m/%Y à %Hh%i\') as corrected_date',
				'conditions' => array('DiaryDetail.diary_id' => $d['diary']->id),
			)
		);

		$this->view->bind($d);
	}

	//AJAX

	public function ajax_status($hash_diary, $id_detail, $mode=1){
		$this->view->setLayout('ajax');
		if($this->Diary->findCount(array('Diary.uniqid' => $hash_diary)) <= 0 || $this->DiaryDetail->findCount(array('DiaryDetail.id' => $id_detail)) <= 0){
			$this->view->bind('message', 'error');
		}else{
			$datas = array('id' => $id_detail, 'corrected_date' => ($mode == 1 ? date("Y-m-d H:i:s",time()) : ''));
			$this->DiaryDetail->save($datas);
			$this->view->bind('message', 'ok');
		}
	}

	//ADMIN

	public function admin_index(){
		$d['diaries'] = $this->Diary->find(array(
			'fields' => 'Diary.id, Diary.name, Diary.content, Diary.slug, Diary.uniqid, DATE_FORMAT(Diary.created, \'%d/%m/%Y à %Hh%i\') as created, DATE_FORMAT(Diary.modified, \'%d/%m/%Y à %Hh%i\') as modified, User.id as user_id, User.login as user_login, User.id as user_id, User.login as user_login, COUNT(DiaryDetail.id) as nb_parties',
			'join' => array(
				'users as User' => 'Diary.user_id = User.id',
				'diary_details as DiaryDetail' => 'DiaryDetail.diary_id = Diary.id',
			),
			'group' => 'Diary.id',
			'order' => 'Diary.created',
		));
		$this->view->bind($d);
	}

	public function admin_add($id = 0){
		if($id > 0){
			if($this->Diary->findCount(array('Diary.id' => $id)) <= 0){
				Core::throwError(404);
			}
			$this->Diary->prefill($id);
		}
		if(isset($this->request->datas['Diary'])){
			$this->Diary->validate($this->request->datas['Diary'], 'add');
			if(empty($this->Diary->validateErrors['add'])){
				if($id > 0){
					$datas = array(
						'name'        => $this->request->datas['Diary']['name'],
						'content'     => $this->request->datas['Diary']['content'],
						'slug'        => $this->request->datas['Diary']['slug'],
						'modified'    => date("Y-m-d H:i:s",time()),
						'modifier_id' => $this->Session->get('User')->id,
						'id'          => $id,
					);
				}else{
					$datas = array(
						'name'    => $this->request->datas['Diary']['name'],
						'content' => $this->request->datas['Diary']['content'],
						'slug'    => $this->request->datas['Diary']['slug'],
						'created' => date("Y-m-d H:i:s",time()),
						'user_id' => $this->Session->get('User')->id,
						'uniqid'  => uniqid(),
					);
				}
				$this->Diary->save($datas);
				if($id <= 0){
					$firstDetail = array(
						'name' => 'Premièrement',
						'content' => 'A compléter.',
						'diary_id' => $this->Diary->id,
						'created' => date("Y-m-d H:i:s",time()),
						'critical' => 0,
					);
					
					$this->DiaryDetail->save($firstDetail);
				}
				$this->Session->setFlash('Votre action a bien été prise en compte.');
				$this->response->redirect('adm/diaries');
			}
		}
		$this->view->bind('id', $id);
	}

	public function admin_delete($id){
		if($this->Diary->findCount(array('Diary.id' => $id)) <= 0){
			Core::throwError(404);
		}
		$this->Diary->delete($id);
		$details = $this->DiaryDetail->find(array(
			'fields' => 'DiaryDetail.id, DiaryDetail.name, DiaryDetail.content, DiaryDetail.critical, DATE_FORMAT(DiaryDetail.created, \'%d/%m/%Y à %Hh%i\') as created, DATE_FORMAT(DiaryDetail.corrected_date, \'%d/%m/%Y à %Hh%i\') as corrected_date',
			'conditions' => array('DiaryDetail.diary_id' => $id),
		));
		foreach ($details as $v) {
			$this->DiaryDetail->delete($v->id);
		}
		$this->Session->setFlash('Le rapport a bien été supprimé.');
		$this->response->redirect('adm/diaries');
	}

	public function admin_details($id){
		$d['diary'] = $this->Diary->findFirst(array(
			'conditions' => array('Diary.id' => $id),
		));
		if(!$d['diary']){
			Core::throwError(404);
		}else{
			$d['details'] = $this->DiaryDetail->find(array(
				'fields' => 'DiaryDetail.id, DiaryDetail.name, DiaryDetail.content, DiaryDetail.critical, DATE_FORMAT(DiaryDetail.created, \'%d/%m/%Y à %Hh%i\') as created, DATE_FORMAT(DiaryDetail.corrected_date, \'%d/%m/%Y à %Hh%i\') as corrected_date',
				'conditions' => array('DiaryDetail.diary_id' => $d['diary']->id),
			));
		}
		$this->view->bind($d);
	}

	public function admin_deletedetail($id){
		$this->DiaryDetail->delete($id);
		$this->Session->setFlash('Le détail a bien été supprimé.');
		$this->response->redirect('adm/diaries');
	}

	public function admin_addetail($diary_id, $id=0){
		if($this->Diary->findCount(array('Diary.id' => $diary_id)) <= 0){
				Core::throwError(404);
		}
		if($id > 0){
			if($this->DiaryDetail->findCount(array('DiaryDetail.id' => $id)) <= 0){
				Core::throwError(404);
			}
			$this->DiaryDetail->prefill($id);
		}
		if(isset($this->request->datas['DiaryDetail'])){
			$this->DiaryDetail->validate($this->request->datas['DiaryDetail'], 'add');

			if(empty($this->DiaryDetail->validateErrors['add'])){
				debug($this->request->datas['DiaryDetail']['critical']);
				if($id > 0){
					$datas = array(
						'name'        => $this->request->datas['DiaryDetail']['name'],
						'content'     => $this->request->datas['DiaryDetail']['content'],
						'created'    => date("Y-m-d H:i:s",time()),
						'critical' => $this->request->datas['DiaryDetail']['critical'],
						'id'          => $id,
					);
				}else{
					$datas = array(
						'name'     => $this->request->datas['DiaryDetail']['name'],
						'content'  => $this->request->datas['DiaryDetail']['content'],
						'created'  => date("Y-m-d H:i:s",time()),
						'critical' => $this->request->datas['DiaryDetail']['critical'],
						'diary_id' => $diary_id,
					);
				}
				$this->DiaryDetail->save($datas);

				$this->Session->setFlash('Votre action a bien été prise en compte.');
				$this->response->redirect('adm/diaries');
			}
		}
		$this->view->bind('id', $id);
	}

}

?>