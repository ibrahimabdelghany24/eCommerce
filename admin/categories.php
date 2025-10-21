<?php 
  session_start();
  $navbar = true;
  $page_title = "Categories";
  if (isset($_SESSION["username"])) {
    include("init.php");
    $action = (isset($_GET["action"]))? $_GET["action"] : "manage";
    if ($action == "manage") {
      $stmt = $con->prepare("SELECT * FROM categories");
      $stmt->execute();
      $cats = $stmt->fetchAll();?>
      <h1 class='text-center'><?php echo lang("MANAGECAT") ?></h1>
      <div class="container categories">
        <div class="panel panel-default">
          <div class="panel-heading">
            <?php echo lang("CATEGORIES")?>
          </div>
          <div class="panel-body">
            <?php
            foreach($cats as $cat):
              echo "<div class='cat'>";
                echo "<div>";
                  echo "<h2 class='cat-name'>{$cat['name']}</h2>";
                  echo "<div>";
                  echo "<a class='btn btn-success' href=?action=edit&catid={$cat["id"]}><i class='fa-solid fa-pen-to-square'></i> " . lang("EDIT") . "</a>";
                  echo "<a class='btn btn-danger' href=?action=delete&catid={$cat["id"]}><i class='fa-solid fa-trash'></i>" . lang("DELETE") . "</a>";
                  echo "</div>";
                echo "</div>";
                echo "<p>" . ((empty($cat["description"]))? "No description" : $cat["description"]) ."</p>";
                echo "<div>";
                  echo (($cat["visibility"])? "<span class='visible'>". lang("VISIBLE") ."</span>" : "<span class='not-visible'>". lang("HIDDEN") ."</span>");
                  echo (($cat["allow_comment"])? "<span class='allow-comment'>". lang("COMALLOWED") ."</span>" : "<span class='comment-disabled'>". lang("COMDISABLED") ."</span>");
                  echo (($cat["allow_ads"])? "<span class='allow-ads'>". lang("ADSALLOWED") ."</span>" : "<span class='ads-disabled'>". lang("ADSDISABLED") ."</span>");
                echo "</div>";
              echo "</div>";
            endforeach;
            ?>
          </div>
        </div>
        <a class="btn btn-primary" href="?action=add"><i class="fa fa-plus"></i> <?php echo lang("ADDCAT") ?></a>
      </div>
    <?php
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
            <span><?php echo lang("VISIBLE") ?>: </span>
            <label>
              <input class="form-control" type="radio" name="visibility" value="1" checked> <?php echo lang("YES")?>
            </label>
            <label>
              <input class="form-control" type="radio" name="visibility" value="0"> <?php echo lang("NO")?>
            </label>
          </div>
          <div>
            <span><?php echo lang("COMALLOWED") ?>: </span>
            <label>
              <input class="form-control" type="radio" name="allow_comment" value="1" checked> <?php echo lang("YES")?>
            </label>
            <label>
              <input class="form-control" type="radio" name="allow_comment" value="0"> <?php echo lang("NO")?>
            </label>
          </div>
          <div>
            <span><?php echo lang("ADSALLOWED") ?>: </span>
            <label>
              <input class="form-control" type="radio" name="allow_ads" value="1" checked> <?php echo lang("YES")?>
            </label>
            <label>
              <input class="form-control" type="radio" name="allow_ads" value="0"> <?php echo lang("NO")?>
            </label>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="<?php echo lang("ADDCAT")?>">
        </form>
      </div>
      <?php
    // end add
    }elseif ($action == "insert") { // insert page
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h1 class='text-center'>" . lang("INSERTCAT") . "</h1>";
        $name = $_POST["name"];
        $description = $_POST["description"];
        $order = (empty($_POST["ordering"]))? NULL: $_POST["ordering"];
        $visible = $_POST["visibility"];
        $allow_comment = $_POST["allow_comment"];
        $allow_ads = $_POST["allow_ads"];
        if (empty($name)) {
          redirect_home([lang("CATNAMEEMPTY")], "back", "danger", false);
        }
        if (is_exist("name", "categories", $name)) {
          redirect_home([lang("CATEXIST")], "back", "danger");
        }else {
          $stmt = $con->prepare("INSERT 
          INTO categories(name, description, ordering, visibility, allow_comment, allow_ads)
          VALUES(?, ?, ?, ?, ?, ?)");
          $stmt->execute([$name, $description, $order, $visible, $allow_comment, $allow_ads]);
          $rows = $stmt->rowCount();
          if ($rows):
            redirect_home([lang("CATADDED")], "back", "success");
          else:
            redirect_home([lang("NOCATADDED")], "back", "danger");
          endif;
        }
      }else {
        redirect_home([lang("CANTBROWSE")], "back", "danger");
      }
    //end insert
    }elseif ($action == "edit") {  // edit page
      $catid = (isset($_GET["catid"])) ? intval($_GET["catid"]) : 0;
      if (is_exist("id", "categories", $catid)) { 
        $stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$catid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h1 class="text-center"><?php echo lang("EDITCAT") ?></h1>
        <div class="container">
          <form class="add-cat form" action="?action=update" method="POST" autocomplete="off">
            <div>
              <label class="feild">
                <span><?php echo lang("CATNAME")?></span>
                <input class="form-control" type="hidden" name="catid" value="<?php echo $row["id"] ?>" placeholder="<?php echo lang("CATNAME")?>" data-text="<?php echo lang("CATNAME")?>" autocomplete="off" required>
                <input class="form-control" type="text" name="name" value="<?php echo $row["name"] ?>" placeholder="<?php echo lang("CATNAME")?>" data-text="<?php echo lang("CATNAME")?>" autocomplete="off" required>
              </label>
            </div>
            <div> 
              <label class="feild">
                <span><?php echo lang("CATDESCRIPTION")?></span>
                <input class="form-control" type="text" name="description" value="<?php echo $row["description"] ?>" placeholder="<?php echo lang("CATDESCRIPTION")?>" data-text="<?php echo lang("CATDESCRIPTION")?>" autocomplete="off">
              </label>
            </div>
            <div>
              <label class="feild">
              <span><?php echo lang("CATORDERING")?></span>
                <input class="form-control" type="text" name="ordering" value="<?php echo $row["ordering"] ?>" placeholder="<?php echo lang("CATORDERING")?>" data-text="<?php echo lang("CATORDERING")?>" autocomplete="off">
              </label>
            </div>
            <div>
              <span><?php echo lang("VISIBLE")?>: </span>
              <label>
                <input class="form-control" type="radio" name="visibility" value="1" <?php if ($row["visible"]) echo "checked";?> > <?php echo lang("YES")?>
              </label>
              <label>
                <input class="form-control" type="radio" name="visibility" value="0" <?php if (!$row["visible"]) echo "checked";?> > <?php echo lang("NO")?>
              </label>
            </div>
            <div>
              <span><?php echo lang("COMALLOWED")?>: </span>
              <label>
                <input class="form-control" type="radio" name="allow_comment" value="1" <?php if ($row["allow_comment"]) echo "checked";?>> <?php echo lang("YES")?>
              </label>
              <label>
                <input class="form-control" type="radio" name="allow_comment" value="0" <?php if (!$row["allow_comment"]) echo "checked";?> > <?php echo lang("NO")?>
              </label>
            </div>
            <div>
              <span><?php echo lang("ADSALLOWED")?>: </span>
              <label>
                <input class="form-control" type="radio" name="allow_ads" value="1" <?php if ($row["allow_ads"]) echo "checked";?> > <?php echo lang("YES")?>
              </label>
              <label>
                <input class="form-control" type="radio" name="allow_ads" value="0" <?php if (!$row["allow_ads"]) echo "checked";?> > <?php echo lang("NO")?>
              </label>
            </div>
            <input class="btn btn-primary btn-block" type="submit" name="submit" value="<?php echo lang("UPDATECAT")?>">
          </form>
        </div>
      <?php
      }else {
        redirect_home([lang("NOCAT")], "back", "danger");
      }
    // end edit
    }elseif ($action == "update") { // update page
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h1 class='text-center'>" . lang('UPDATECAT') ."</h1>";
        $catid = $_POST["catid"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $order = (empty($_POST["ordering"]))? NULL: $_POST["ordering"];
        $visible = $_POST["visibility"];
        $allow_comment = $_POST["allow_comment"];
        $allow_ads = $_POST["allow_ads"];
        if (empty($name)) {
          redirect_home([lang("CATNAMEEMPTY")], "back", "danger");
        }else {
          $stmt = $con->prepare("UPDATE categories 
          SET 
          name = ?, description = ?, ordering = ?, visibility = ?, allow_comment = ?, allow_ads = ? 
          where id = ?");
          $stmt->execute([$name, $description, $order, $visible, $allow_comment, $allow_ads, $catid]);
          $count = $stmt->rowCount();
          if ($count):
            redirect_home([lang("CATUPDATED")], "back", "success");
          else:
            redirect_home([lang("NOCATUPDATED")], "back", "danger");
          endif;
        }
      }else {
        redirect_home([lang("CANTBROWSE")], "back", "danger", false);
      }
    // end update
    }elseif ($action == "delete") { // delete
      
    }
    // end delete
    include($tpl . "footer.php");
  }else {
    header("location: index.php");
    exit();
  }