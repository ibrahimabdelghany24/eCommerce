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
  function redirect_home($msg, $url=null, $class) {
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
      $title = "Error ";
    }else {
      $title = "";
    }
    if (!empty($msg)):
      echo "<div class='alert alert-$class text-center' role='alert'>";
      echo "<h2 class='alert-heading'>{$title}Message</h2>";
      echo "<hr>";
      foreach ($msg as $p):
      echo "<p style='font-size:20px'>$p</p><hr>";
      endforeach;
      echo "<a class='btn btn-primary' href='$url'>Go Back</a>";
      echo "</div>";
    endif;
  }
  // Check if exist
  function is_exist($item, $table, $value) {
    global $con;
    $statement = $con->prepare("SELECT $item FROM $table WHERE $item = ?");
    $statement->execute([$value]);
    return $statement->rowCount();
  }
// Count number of item
  function count_item($item, $table, $condition = "") {
    global $con;
    $stmt = $con->prepare("SELECT COUNT($item) FROM {$table} {$condition}");
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  // function get_latest
  function get_latest($item, $table, $order, $limit, $condition="") {
    global $con;
    $statement = $con->prepare("SELECT {$item}
                                FROM {$table} 
                                {$condition}
                                ORDER BY {$order} DESC
                                LIMIT {$limit}");
    $statement->execute();
    $rows = $statement->fetchAll();
    return $rows;
  }