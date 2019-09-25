<?php
/*
 * 功能：mysql pdo 操作类
 * 版本：2.2
 * 时间：2017-04-20
 */
class db{
    private $pdo;
	public $config;
	public $debug = false;
	
    //构造函数
    function __construct($config){
		$this->config = $config;
		if(!$this->config['host'] || !$this->config['user']){
			echo '数据库配置参数错误';
			return;
		}
		$this->connect();
		
		$_GET['debug'] = isset($_GET['debug']) ? $_GET['debug'] : '';
		if($_GET['debug']){
			$this->debug = true;
		}
    }
	
	//数据库连接
	function connect(){
		try{
			$this->pdo = new PDO('mysql:host='.$this->config['host'].';port='.$this->config['port'].';dbname='.$this->config['db'].';charset='.$this->config['char'], $this->config['user'], $this->config['pwd']);
			$this->pdo->exec('SET NAMES '.$this->config['char']); 
			return true;
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
	
	//查询
    function query($sql){
		$rs = $this->pdo->query($sql);
		$this->errorLog();
		return $rs;
    }
	
	//获取一个字段
	function getOne($sql){
		$rs = $this->pdo->query($sql);
		if(!$rs){
			return '';
		}
		
		$temp = $rs->fetchColumn();
		
		return $temp;
	}
	
	//获取一行
	function getRow($sql){
		$rs = $this->pdo->query($sql);
		if(!$rs){
			return array();
		}
		
		$temp = $rs->fetch(PDO::FETCH_ASSOC);
		if(empty($temp)){
			return array();
		}
		
		return $temp;
	}
	
	//获取全部
	function getAll($sql){
		$rs = $this->pdo->query($sql);
		if(!$rs){
			return array();
		}
		
		$temp = $rs->fetchAll(PDO::FETCH_ASSOC);
		if(empty($temp)){
			return array();
		}
		
		return $temp;
	}
	
	//获取分页数据
	function getLimit($sql, $page_start, $page_size){
		$sql .= ' limit '.$page_start.', '.$page_size;

		$rs = $this->pdo->query($sql);
		if(!$rs){
			return array();
		}
		$temp = $rs->fetchAll(PDO::FETCH_ASSOC);
		if(empty($temp)){
			return array();
		}
		
		return $temp;
	}
	
	//插入ID
	function getInsertId(){
		$id = $this->pdo->lastInsertId();
		$this->errorLog();
		return $id;
	}
	
	//释放资源
	function close(){
		$this->pdo = NULL;
	}
	
	//析构函数
	function __destruct(){
		//$this->close();
	}
	
	//错误日志
	function errorLog(){
		if(!$this->debug){
			return;
		}
		print_r($this->pdo->errorInfo());
	}
}
?>