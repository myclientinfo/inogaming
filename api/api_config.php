<?php
$dbhost = "localhost";
$db = "inogaming";
$dbuser = "root";
$dbpass = "";

$dbh = mysql_connect ("localhost", $dbuser, $dbpass) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($db) or die('cant connect');
mysql_query("SET NAMES 'utf8'");

ini_set("mbstring.func_overload", 7);
session_start();

include_once("../class.site.php");
include_once("../class.data.php");
include_once("../class.location.php");
include_once("../class.debug.php");
include_once("../class.rss.php");

$GLOBALS['debug'] = new Debug(false);
$GLOBALS['debug']->debugStatus(true);
?>