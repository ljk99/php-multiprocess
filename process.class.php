<?
$fn=basename($_SERVER["SCRIPT_NAME"]);

if($fn<>"process.class.php"){
	/*
	require_once("./lib/func.general.php");
	require_once("./lib/Snoopy.class.php");
	require_once("./lib/sql.lib.php");
	require_once("./lib/sqlite.lib.php");
	$dbdir="../sqlite/sqlite/";
	*/
	$process= new process;
}else{
	date_default_timezone_set("Asia/Shanghai");
	if(!(PHP_OS=="Linux" &&  isset($_SERVER["ANDROID_ROOT"]))){
		ignore_user_abort(true);
		set_time_limit(0);
	}
	ini_set("max_execution_time",60*60*60); 
	ini_set("extension","php_pdo.dll"); 
	//ini_set("error_reporting","E_ALL ~E_NOTICE"); 
	ini_set("extension","php_pdo_sqlite.dll"); 
	ini_set("extension","php_sqlite.dll"); 
	require_once("../lib/func.general.php");
	require_once("../lib/sql.lib.php");
	require_once("../lib/sqlite.lib.php");
	$process= new process;
	if($_SERVER["HTTP_HOST"]<>""){?>
<A HREF="?actfunc=start">start</A>
<A HREF="?">showlist</A>
<pre>
	<?
	}
	if(!empty($actfunc)){
		//echo 
		$streval='$process->'.$actfunc."();";
		eval($streval);
		//exit;
	}
	$process->showlist();
}
//================================
//$process->start();
/*
	global $process;
	$process->start();
	$i=1;

		$i++;
		$process->check2stop($i);



*/


//================================
class process{
	var $DB_process; 
	var $processid;
	function __construct(){
		if(file_exists("./lib/func.general.php")){
			$dbdir="../sqlite/sqlite/";
		}else{
			$dbdir="../../sqlite/sqlite/";
		}
		//echo 
		$dbname_process=$dbdir."sys_process.db3";
		$this->DB_process=new SQLite($dbname_process); 
		$this->tbl_create_process();	
	}
	function __destruct(){
		$this->stop(false);
	}

	function start(){
		$A_row["processid"]=$this->processid=uniqid();
		$A_row["filename"]=$_SERVER["SCRIPT_NAME"] ;
		$A_row["query"]=$_SERVER["QUERY_STRING"];
		$time_current=date('Y-m-d H:i:s');
		$A_row["createtime"]=$time_current;
		$A_row["updatetime"]=$time_current;
		$A_row["status"]=1;
		$sql=make_sql_additem($A_row);
		//echo 
		$sql="insert into \"process\"  ".$sql;
		$this->DB_process->query($sql);
	}
	function setstop(){
		$time_current=date('Y-m-d H:i:s');
		if(!empty($this->processid)){
			$processid=$this->processid;
		}else{
			$processid=$_GET["processid"];
		}
		$sql="update process set updatetime='$time_current' ,status='stop' where  processid='$processid'";
		$this->DB_process->query($sql);
	}
	function stop($stop=true){
		if(!empty($this->processid)){
			$processid=$this->processid;
		}else{
			$processid=$_GET["processid"];
		}
		$sql="delete from process  where  processid='".$processid."'";
		$this->DB_process->query($sql);
		if(empty($_GET["processid"])){
			if($stop)exit("stop");
		}
	}

	function showlist(){
		$sql="select * from process order by updatetime desc";
		$A_all=$this->DB_process->queryall($sql);
		//print_r($A_all);
		if(!empty($A_all)){
			foreach($A_all as $A_row){
				$processid=$A_row["processid"];
				$filename=$A_row["filename"];
				$createtime=$A_row["createtime"];
				$A_row["processid"]="<A HREF=\"?actfunc=setstop&processid=$processid\">$processid</A>";
				$A_row["filename"]="<A HREF=\"?actfunc=check2stop&processid=$processid\">$filename</A>";
				$A_row["createtime"]="<A HREF=\"?actfunc=stop&processid=$processid\">$createtime</A>";
				$A_all2[]=$A_row;
			}
			echo "<pre>";
			list_2array($A_all2);
		}
	}

	function check2stop($status=1){
		if(!empty($this->processid)){
			$processid=$this->processid;
		}else{
			$processid=$_GET["processid"];
		}
		//echo 
		$sql="select  status from process where processid='".$processid."'";
		$status_current=$this->DB_process->queryitem($sql);
		if($status_current<>"stop" && $status_current<>""){
			$time_current=date('Y-m-d H:i:s');
			$sql="update process set updatetime='$time_current' ,status='$status' where  processid='".$processid."'";
			$this->DB_process->query($sql);
			return true;
		}else{
			$this->stop();
			return false;
		}
	}

	function tbl_create_process(){
		$sql="
			CREATE TABLE process
			(
			[processid] char(30) primary key,
			[filename] char(100),
			[query] char(300),
			[createtime] datetime,
			[updatetime] datetime,
			[status] char(20) default 0
			);
		";
		$this->DB_process->query($sql);
	}






}













?>