<?php
  function lang($phrase) {
    static $lang = array(
      // Navebar
      "HOME"       => "الصفحة الرئيسبة",
      "CATEGORIES" => "Sections",
      "ITEMS"      => "Items",
      "MEMBERS"    => "الأعضاء",
      "STATISTICS" => "الاحصائيات",
      "LOGS"       => "Logs",
      "EDITPROFILE" => "تعديل الصفحة الشخصية",
      "SETTINGS" => "الاعدادات",
      "LOGOUT" => "تسجيل الخروج",
      "" => "",
      "" => "",
      "" => "",
      "" => "",
    );
    return $lang[$phrase];
  }