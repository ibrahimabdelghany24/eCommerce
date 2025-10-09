<?php
  include("connect.php");
  // routes
  $tpl = "includes/templates/";
  $css = "layout/css/";
  $js = "layout/js/";
  $lang = "includes/languages/";
  // include files
  include($lang . "en.php");
  include($tpl . "header.php");
  if ($navbar) {
    include($tpl . "navbar.php");
  }