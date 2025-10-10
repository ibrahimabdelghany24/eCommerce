<?php 
  session_start();
  $navbar = true;
  $page_title = "Dashboard";
  if (isset($_SESSION["username"])){
    include("init.php");
    echo "<h1>Hello " . ucwords($_SESSION["username"]) . "</h1>";
    include($tpl . "footer.php");
  } else{
    header("location: index.php");
    exit();
  }
