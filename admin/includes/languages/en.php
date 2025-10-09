<?php
  function lang($phrase) {
    static $lang = array(
      // Navebar
      "HOME"       => "Home Page",
      "CATEGORIES" => "Sections",
      "ITEMS"      => "Items",
      "MEMBERS"    => "Members",
      "STATISTICS" => "Statistics",
      "LOGS"       => "Logs",
      "" => "",
      "" => "",
      "" => "",
      "" => "",
      "" => "",
      "" => "",
      "" => "",
    );
    return $lang[$phrase];
  }