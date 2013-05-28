<?php
if($_SERVER['HTTP_HOST']=='inogaming'){
	$dbhost = "localhost";
	$db = "inogaming";
	$dbuser = "root";
	$dbpass = "";
} else {
	$dbhost = "ino-gaming.mysql.eu1.frbit.com";
	$db = "ino-gaming";
	$dbuser = "ino-gaming";
	$dbpass = "1fhxs3LafcCx8z7W";

	//$dbpass = '';
}


$dbh = mysql_connect ($dbhost, $dbuser, $dbpass) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($db) or die('cant connect'); 
mysql_query("SET NAMES 'utf8'");

ini_set("mbstring.func_overload", 7);
session_start();

include_once("class.site.php");
include_once("class.data.php");
include_once("class.location.php");
include_once("class.debug.php");
include_once("class.template.php");
include_once("class.auth.php");
include_once("class.rss.php");
include_once("class.javascript.php");

$GLOBALS['debug'] = new Debug(false);
$GLOBALS['debug']->debugStatus(true);
?>