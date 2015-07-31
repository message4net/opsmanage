<?php
/** private
功能:数据库的基础操作类
**/
class DBSql{
private $CONN="";//定义数据库连接变量
/**
	*功能:构造函数，连接数据库
	*/
	public function __construct(){
	 $this->usedb(DBHost, DBUser, DBPasswd);
	}
/**
 * 功能:选择数据库
 * 参数:$dbhost,$dbuser,$dbpasswd(数据库ip:端口,数据库用户,数据库密码)
 */
	public function usedb($dbhost,$dbuser,$dbpasswd){
		$connect=mysql_connect($dbhost,$dbuser,$dbpasswd) or die("无法连接数据库1");
		mysql_query("set names 'utf8'");
		mysql_select_db(DBName) or die ("无法选择数据库");
		$this->CONN=$connect;
	}
/**
	*功能:数据库查询函数
	*参数:$sqlSQL语句
	*返回:二维数组或FALSE
	*/
	public function select($sql){
		if(empty($sql)) return false;//如果SQL语句为空，则返回FALSE
		if(empty($this->CONN)) return false;//如果连接为空，则放回FALSE
		$results=mysql_query($sql,$this->CONN);
		if((!$results)or(empty($results))){//如果查询结果空则释放结果并返回FALSE
		@mysql_free_result($results);
		return false;
	}
	$count=0;
	$data=array();
	while($row=@mysql_fetch_assoc($results)){//把查询结果重组成一个而为数组
	$data[$count]=$row;
	$count++;
	}
	@mysql_free_result($results);
//var_dump($data);
	return $data;
}
/**
	*功能:数据插入函数
	*参数:$sqlSQL语句
	*返回:0或新插入数据的ID
	*/
	public function insert($sql=""){
		if(empty($sql)) return 0;//如果SQL语句为空则返回FALSE
		if(empty($this->CONN)) return 0;//如果连接为空，则返回FALSE
		try{//捕获数据库选择错误并显示错误文件
			$results=mysql_query($sql,$this->CONN);
		}catch(Exception$e){
			$msg=$e;
			include(ErrFile);
		}
		if(!$results) return 0;//如果插入失败返回0,否则返回当前插入数据ID
		else return @mysql_insert_id($this->CONN);
	}
/**
	*功能:数据更新函数
	*参数:$sqlSQL语句
	*返回:TRUEORFALSE
	*/
	public function update($sql=""){
		if(empty($sql)) return false;//如果SQL语句为空则返回FALSE
		if(empty($this->CONN)) return false;//如果连接为空则返回FALSE
		try{//捕获数据库选择错误并显示错误文件
			$result=mysql_query($sql,$this->CONN);
		}catch(Except$e){
			$msg=$e;
			include(ERRFILE);
		}
		return $result;
	}
/**
	*功能:数据删除函数
	*参数:$sqlSQL语句
	*返回:TRUEORFALSE
	*/
	public function delete($sql=""){
		if(empty($sql)) return false;//如果SQL语句为空则返回FALSE
		if(empty($this->CONN)) return false;//如果连接为空则返回FALSE
		try{//捕获数据库选择错误并显示错误文件
			$result=mysql_query($sql,$this->CONN);
		}catch(Except$e){
			$msg=$e;
			include(ERRFILE);
		}
		return $result;
	}
/**
	*功能:定义事务
	*/
	public function begintransaction(){
	mysql_query("SETAUTOCOMMIT=0");//设置为不自动提交，mysql默认立即执行
	mysql_query("BEGIN");//开始事务定义
	}
/**
	*功能:回滚
	*/
	public function rollback(){
		mysql_query("ROLLBACK");
	}
/**
	*功能:提交执行
	*/
	public function commit(){
		mysql_query("COMMIT");
		}
	}
?>
