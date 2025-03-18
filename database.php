<?php
$server = 'localhost';
$user = 'root';
$pass= '';
$db = 'University Database';

$myconnection = mysqli_connect($server, $user, $pass) or die('Could not connect:' .mysql_error());
$db = mysqli_select_db($myconnection, $db) or die('Could not select database');
?>