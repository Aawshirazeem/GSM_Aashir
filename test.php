<?php
$con = mysql_connect('127.0.0.1','imeipk_dbaccess','$uperm@n2016');
if($con){
	echo "Connection Success!";
}else{
	echo "Connection Failed!";
}
/*$dbDetails = mysql_query('SELECT * FROM sys.databases');
while ($result = mysql_fetch_array($dbDetails)){
	print_r($result);
}
die;*/
$checkDb = mysql_select_db('imeipk_dbnew5555');
if($checkDb){
	echo 'DB Find';
}else{
	echo 'DB Not Find';
}
?>