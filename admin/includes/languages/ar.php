<?php
  function lang($phrase) {
    static $lang = array(
      "HOME" => "الصفحة الرئيسية",
      "MESSAGE" => "مرحبا"
    );
    return $lang[$phrase];
  }