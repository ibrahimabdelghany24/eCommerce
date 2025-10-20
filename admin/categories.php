<?php 
  session_start();
  $navbar = true;
  $page_title = "Categories";
  if (isset($_SESSION["username"])) {
    include("init.php");
    $action = (isset($_GET["action"]))? $_GET["action"] : "manage";
    if ($action == "manage") {
      echo "<h1 class='text-center'>Manage</h1>";
    }elseif ($action == "add") { // add page?>
      <h1 class="text-center"><?php echo lang("ADDCAT") ?></h1>
      <div class="container">
        <form class="add-cat form" action="?action=insert" method="POST" autocomplete="off">
          <div>
            <label class="feild">
              <span><?php echo lang("CATNAME")?></span>
              <input class="form-control" type="text" name="name" placeholder="<?php echo lang("CATNAME")?>" data-text="<?php echo lang("CATNAME")?>" autocomplete="off" required>
            </label>
          </div>
          <div> 
            <label class="feild">
              <span><?php echo lang("CATDESCRIPTION")?></span>
              <input class="form-control" type="text" name="description" placeholder="<?php echo lang("CATDESCRIPTION")?>" data-text="<?php echo lang("CATDESCRIPTION")?>" autocomplete="off">
            </label>
          </div>
          <div>
            <label class="feild">
            <span><?php echo lang("CATORDERING")?></span>
              <input class="form-control" type="text" name="ordering" placeholder="<?php echo lang("CATORDERING")?>" data-text="<?php echo lang("CATORDERING")?>" autocomplete="off">
            </label>
          </div>
          <div>
            <span>Visibile: </span>
            <label>
              <input class="form-control" type="radio" name="visibility" value="1" checked> Yes
            </label>
            <label>
              <input class="form-control" type="radio" name="visibility" value="0"> No
            </label>
          </div>
          <div>
            <span>Allow comments: </span>
            <label>
              <input class="form-control" type="radio" name="allow_comment" value="1" checked> Yes
            </label>
            <label>
              <input class="form-control" type="radio" name="allow_comment" value="0"> No
            </label>
          </div>
          <div>
            <span>Allow ads: </span>
            <label>
              <input class="form-control" type="radio" name="allow_ads" value="1" checked> Yes
            </label>
            <label>
              <input class="form-control" type="radio" name="allow_ads" value="0"> No
            </label>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="<?php echo lang("ADDCAT")?>">
        </form>
      </div>
      <?php
    // end add
    }elseif ($action == "insert") { // insert page
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h1 class='text-center'>Insert Category</h1>";
        $name = $_POST["name"];
        $description = $_POST["description"];
        $order = $_POST["order"];
        $visible = $_POST["visibility"];
        $allow_comment = $_POST["allow_comment"];
        $allow_ads = $_POST["allow_ads"];
        if (empty($name)) {
          redirect_home(["Category name can't be empty"], "back", "danger", false);
        }
        if (is_exist("name", "categories", $name)) {
          redirect_home(["This category name is already exist."], "back", "danger");
        }else {
          $stmt = $con->prepare("INSERT 
          INTO categories(name, description, ordering, visibility, allow_comment, allow_ads)
          VALUES(?, ?, ?, ?, ?, ?)");
          $stmt->execute([$name, $description, $order, $visible, $allow_comment, $allow_ads]);
          $rows = $stmt->rowCount();
          redirect_home(["$rows Category inserted"], "back", "success");
        }
      }else {
        redirect_home([lang("CANTBROWSE")], "back", "danger");
      }
    //end page
    }elseif ($action == "edit") {
      
    }elseif ($action == "update") {
      
    }elseif ($action == "delete") {
      
    }
    include($tpl . "footer.php");
  }else {
    header("location: index.php");
    exit();
  }