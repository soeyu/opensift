<?php
$dbhost = getenv("MYSQL_SERVICE_SERVICE_HOST");
$dbuser = 'root';
$dbpwd = getenv("MYSQL_PASSWORD");
$dbname = getenv("MYSQL_USER");
$conn=mysqli_connect($dbhost.":3306",$dbuser,$dbpwd);
if(!$conn){echo "no _";}
else{
	echo "done";
}
mysql_select_db($dbname,$conn);
mysql_query("set names uft8");
?>