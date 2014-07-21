<?php

class Model_SW{
	
	public static $databases = array();
	public $Db;
	public $selectedDb       = 'default';
	public $prefix;
	public $table;
	public $primaryKey;
	public $id;
	public $validateRules    = array();
	public $validateErrors   = array();
	public $lastSql; 
	public $prefilled        = array();                

	public function __construct(){
		$modelName = get_class($this); 
		$this->primaryKey = Config::get('PREFIX_PRIMARY_KEY') ? strtolower($modelName[0]).'_id' : 'id';
		if(empty($table)){
			if(strlen(preg_replace('[^A-Z]', '', $modelName)) > 1){
				$modelName = substr(preg_replace('#([A-Z])#', '_$1', $modelName), 1);
			}
			$modelName = strtolower($modelName);
			if($modelName[strlen($modelName)-1] == 'y'){
				$this->table = substr($modelName,0,strlen($modelName)-1).'ies'; 
			}else{
				$this->table = $modelName.'s'; 
			}
		}
		
		if(isset(Model_SW::$databases[$this->selectedDb])){
			$this->Db = Model_SW::$databases[$this->selectedDb];
		}else{
			try{
				$conf = Config::get('DATABASES');
				$conf = $conf[$this->selectedDb];
				$this->Db = new PDO(
					'mysql:host='.$conf['host'].';dbname='.$conf['database'].';',
					$conf['user'],
					$conf['password'],
					array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
				);
				$this->Db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
				Model_SW::$databases[$this->selectedDb] = $this->Db; 
			}catch(PDOException $e){
				unset($this->Db);
				if(Config::get('DEBUG_MODE') >= 1){
					die($e->getMessage()); 
				}else{
					die('Impossible de se connecter à la base de données'); 
				}
			}
		}
	}


	public function prefill($primary_key){
		$select = $this->findFirst(array(
			'conditions' => array(get_class($this).'.'.$this->primaryKey => $primary_key),
		));
		foreach ($select as $k => $v) {
			$this->prefilled[$k] = $v;
		}
	}

	public function validate($datas, $name){
		foreach ($this->validateRules[$name] as $key => $value) {
			if(!isset($datas[$key])){
				$this->validateErrors[$name][$key] = $value['message'];
			}else{
				if($value['rule'] == 'same'){
					if(!($datas[$key] == $datas[$value['relation']])){
						$this->validateErrors[$name][$key] = $value['message'];
					}
				}else if($value['rule'] == 'notEmpty'){
					if(empty($datas[$key])){
						$this->validateErrors[$name][$key] = $value['message'];
					}else if(isset($value['min']) && strlen($datas[$key]) < $value['min']){
						$this->validateErrors[$name][$key] = $value['message'];
					}
				}
				else if(!preg_match('#^'.$value['rule'].'$#', $datas[$key])){
					$this->validateErrors[$name][$key] = $value['message'];
				}
			}
		}
	}

	public function find($req = array()){
		$sql = 'SELECT ';

		if(isset($req['fields'])){
			if(is_array($req['fields'])){
				$sql .= implode(', ',$$req['fields']);
			}else{
				$sql .= $req['fields']; 
			}
		}else{
			$sql.='*';
		}

		$sql .= ' FROM '.$this->table.' as '.get_class($this).' ';

		// Liaison
		if(isset($req['join'])){
			foreach($req['join'] as $k=>$v){
				$sql .= 'LEFT JOIN '.$k.' ON '.$v.' '; 
			}
		}

		// Construction de la condition
		if(isset($req['conditions'])){
			$sql .= 'WHERE ';
			if(!is_array($req['conditions'])){
				$sql .= $req['conditions']; 
			}else{
				$cond = array(); 
				foreach($req['conditions'] as $k=>$v){
					if(!is_numeric($v)){
						$v = '"'.mysql_escape_string($v).'"'; 
					}
					
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ',$cond);
			}

		}

		if(isset($req['group'])){
			$sql .= ' GROUP BY '.$req['group'];
		}

		if(isset($req['order'])){
			$sql .= ' ORDER BY '.$req['order'];
		}


		if(isset($req['limit'])){
			$sql .= ' LIMIT '.$req['limit'];
		}

		$this->lastSql = $sql;
		$pre = $this->Db->prepare($sql); 
		@$pre->execute(); 
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}


	/**
	* Alias permettant de retrouver le premier enregistrement
	**/
	public function findFirst($req){
		return current($this->find($req)); 
	}

	/**
	*Permet de savoir si les informations envoyées en param sont uniques en base de données
	**/
	function checkSingle($attr, $formName = null){
		$errors = array();
		foreach ($attr as $k => $v) {
			if($this->findCount(array('User.'.$k => $v)) > 0){
				$errors[$k] = true;
				if($formName != null){
					$this->validateErrors[$formName][$k] = 'La valeur entrée est déjà utilisée pour un autre compte !';
				}
			}
		}
		return $errors;
	}

	/**
	* Récupère le nombre d'enregistrement
	**/
	public function findCount($conditions, $options = array()){
		$req = array(
			'fields' => 'COUNT('.get_class($this).'.'.$this->primaryKey.') as count',
			'conditions' => $conditions
			);
		if(!empty($options))
			$req = array_merge($req, $options);
		$res = $this->findFirst($req);
		return $res->count;  
	}

	/**
	* Permet de récupérer un tableau indexé par primaryKey et avec name pour valeur
	**/
	function findList($req = array()){
		if(!isset($req['fields'])){
			$req['fields'] = $this->primaryKey.',name';
		}
		$d = $this->find($req); 
		$r = array(); 
		foreach($d as $k=>$v){
			$r[current($v)] = next($v); 
		}
		return $r; 
	}

	/**
	* Permet de supprimer un enregistrement
	* @param $id ID de l'enregistrement à supprimer
	**/	
	public function delete($id){
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
		$this->Db->query($sql); 
	}


	/**
	* Permet de sauvegarder des données
	* @param $data Données à enregistrer
	**/
	public function save($data){
		$key = $this->primaryKey;
		$fields =  array();
		$d = array(); 
		foreach($data as $k=>$v){
			if($k!=$this->primaryKey){
				$fields[] = "$k=:$k";
				$d[":$k"] = $v; 
			}elseif(!empty($v)){
				$d[":$k"] = $v; 
			}
		}
		if(isset($data[$key]) && !empty($data[$key])){
			$sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields).' WHERE '.$key.'=:'.$key;
			$this->id = $data[$key]; 
			$action = 'update';
		} else{
			$sql = 'INSERT INTO '.$this->table.' SET '.implode(',',$fields);
			$action = 'insert'; 
		}
		$pre = $this->Db->prepare($sql); 
		$pre->execute($d);
		if($action == 'insert'){
			$this->id = $this->Db->lastInsertId(); 
		}
	}

	/**
	*Récupérer un résultat tout formaté
	**/
	function findFormated($req = array(), $forme){
		$query = $this->find($req);
		$return = '';
		preg_match_all('#\{(.*)\}#U',$forme,$search,PREG_PATTERN_ORDER);
        foreach($query as $rect){
            for($i=0; $i < count($search[1]); $i++) {
                if(empty($dernier_return)) {
                    $dernier_return = str_replace($search[0][$i],$rect->$search[1][$i],$forme);
                }
                else {
                    $dernier_return = str_replace($search[0][$i],$rect->$search[1][$i],$dernier_return);
                }
           	}
            $return .= $dernier_return."\n";
            unset($dernier_return);
        }
        return $return;
	}

}