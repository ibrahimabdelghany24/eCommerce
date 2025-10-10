<?php
  $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
  if ($action == "manage") {
    echo "Manage page";
    echo "<a href=?action=add>Add Page</a>";
  }elseif ($action == "add") {
    echo "Add page";
  }