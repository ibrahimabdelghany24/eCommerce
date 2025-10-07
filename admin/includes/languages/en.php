<?php
  function lang($phrase) {
    static $lang = array(
      "HOME" => "Home Page",
      "MESSAGE" => "Welcome"
    );
    return $lang[$phrase];
  }