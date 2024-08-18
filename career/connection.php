<?php

define('SITEURL', '/career/');
$host = "localhost";

$db_user = "root";
$db_password = "";
$db_name = "shineairways";

// $db_user = "shineairways";
// $db_password = "Jyoti#@123#jd&@";
// $db_name = "shineairways";
$con =  mysqli_connect($host, $db_user, $db_password, $db_name) or die("connection error: " . mysqli_connect_error());
