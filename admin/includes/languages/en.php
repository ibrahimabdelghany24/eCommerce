<?php
  function lang($phrase) {
    static $lang = array(
      // Navebar
      "HOME"       => "Home Page",
      "CATEGORIES" => "Categories",
      "ITEMS"      => "Items",
      "MEMBERS"    => "Members",
      "STATISTICS" => "Statistics",
      "LOGS"       => "Logs",
      "EDITPROFILE" => "Edit Profile",
      "SETTINGS" => "Settings",
      "LOGOUT" => "Log out",
      "ADMINLOGIN" => "Admin Login",
      "" => "",
      "" => "",
      "" => "",
    );
    return $lang[$phrase];
  }