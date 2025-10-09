<?php 
  session_start();
  $navbar = true;
  include("init.php");
  if (isset($_SESSION["fullname"]))
    echo "<h1>Hello " . ucwords($_SESSION["fullname"]) . "</h1>";
  else{
    header("location: index.php");
    exit();
  }
  include($tpl . "footer.php");
