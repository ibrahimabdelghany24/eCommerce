<?php
$dns = "mysql:host=localhost;dbname=shop";
$dbusername = "root";
$dbpassword = "011970";
$option = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try {
  $con = new PDO($dns, $dbusername, $dbpassword, $option);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
  echo "Failed to connect" . $e->getMessage();
}