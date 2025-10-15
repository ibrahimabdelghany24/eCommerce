<?php
  function get_title() {
    global $page_title;
    if (isset($page_title)) {
      echo $page_title;
    }else {
      echo "Default";
    }
  }

  // redirect function
  function redirect_home($msg, $url=null, $class, $seconds=3) {
    if ($url === null){
      $url = "index.php";
    }elseif ($url == "back"){
      if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
      }else {
        $url = "index.php";
      }
    }
    if ($class == "danger") {
      $title = "Error";
    }else {
      $title = "";
    }
    echo "<div class='alert alert-$class' role='alert'>";
    echo "<h3 class='alert-heading'>$title Message</h3>";
    echo "<p>$msg</p><hr>";
    echo "<p class='mb-0'>You will be redirected to Homepage after $seconds seconds.</p>";
    echo "</div>";
    header("refresh:$seconds; url=$url");
    exit();
  }
  // Check if exist
  function is_exist($item, $table, $value) {
    global $con;
    $statement = $con->prepare("SELECT $item FROM $table WHERE $item = ?");
    $statement->execute([$value]);
    return $statement->rowCount();
  }