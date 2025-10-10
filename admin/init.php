<?php
  include("connect.php");
  // routes
  $tpl = "includes/templates/";
  $css = "layout/css/";
  $js = "layout/js/";
  $lang = "includes/languages/";
  $func = "includes/functions/";
  // include files
  include($func . "func.php");
  include($lang . "en.php");
  include($tpl . "header.php");
  if ($navbar) {
    include($tpl . "navbar.php");
  }