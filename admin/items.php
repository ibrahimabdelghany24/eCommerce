<?php 
  session_start();
  $page_title = "Items";
  $navbar = true;
  if (isset($_SESSION["userid"])) {
    include("init.php");
    $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
    if ($action == "manage") {
      echo "<h1 class='text-center'>Item Page" . "</h1>";
      echo "<a href='?action=add'>add item</a>";
    }elseif ($action == "add") { // add page?>
      <h1 class="text-center"><?php echo lang("ADDITEM") ?></h1>
      <div class="container">
        <form class="add-item form" action="?action=insert" method="POST">
          <div>
            <label class="feild">
              <span><?php echo lang("ITEMNAME")?></span>
              <input 
                class="form-control" 
                type="text" 
                name="name" 
                placeholder="<?php echo lang("ITEMNAME")?>"
                data-text="<?php echo lang("ITEMNAME")?>" 
                >
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?php echo lang("ITEMDESCRIPTION")?></span>
              <input 
                class="form-control" 
                type="text" name="description" 
                placeholder="<?php echo lang("ITEMDESCRIPTION")?>" 
                data-text="<?php echo lang("ITEMDESCRIPTION")?>">
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?php echo lang("ITEMPRICE")?></span>
              <input 
                class="form-control" 
                type="text" name="price" 
                placeholder="<?php echo lang("ITEMPRICE")?>" 
                data-text="<?php echo lang("ITEMPRICE")?>">
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?php echo lang("MADEIN")?></span>
              <input 
                class="form-control" 
                type="text" name="madein" 
                placeholder="<?php echo lang("MADEIN")?>" 
                data-text="<?php echo lang("MADEIN")?>">
            </label>
          </div>
          <div>
            <label class="feild status">
              <span><?php echo lang("ITEMSTATUS")?></span>
              <select name="status">
                <option value="" selected disabled>Select Status</option>
                <option value="1"><?php echo lang("NEW") ?></option>
                <option value="2"><?php echo lang("LIKENEW") ?></option>
                <option value="3"><?php echo lang("USED") ?></option>
              </select>
            </label>
          </div>
          <div>
            <label class="feild status">
              <span><?php echo lang("ITEMOWNER")?></span>
              <select name="owner">
                <option value="" selected disabled>Select User</option>
                <?php
                  $stmt = $con->prepare("SELECT userid, username FROM users");
                  $stmt->execute();
                  $users = $stmt->fetchAll();
                  foreach($users as $user):
                    echo "<option value='{$user["userid"]}'>{$user["username"]}</option>";
                  endforeach;
                ?>
              </select>
            </label>
          </div>
          <div>
            <label class="feild status">
              <span><?php echo lang("ITEMCAT")?></span>
              <select name="cat">
                <option value="" selected disabled>Select Category</option>
                <?php
                  $stmt = $con->prepare("SELECT id, name FROM categories");
                  $stmt->execute();
                  $cats = $stmt->fetchAll();
                  foreach($cats as $cat):
                    echo "<option value='{$cat["id"]}'>{$cat["name"]}</option>";
                  endforeach;
                ?>
              </select>
            </label>
          </div>
          <input class="btn btn-primary btn-block"
          type="submit" name="submit" 
          value="<?php echo lang("ADDITEM")?>">
        </form>
      </div>
      <?php
    // end add
    }elseif ($action == "insert") {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h1 class='text-center'>" . lang("INSERTITEM") . "</h1>";
        $name = $_POST["name"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $made_in = $_POST["madein"];
        $status = $_POST["status"];
        $owner = $_POST["owner"];
        $cat = $_POST["cat"];
        $errors = [];
        if (empty($name)) {
          $errors[] = "Item name can't be <strong>empty</strong>";
        }
        if (empty($description)) {
          $errors[] = "Item description can't be <strong>empty</strong>";
        }
        if (empty($price)) {
          $errors[] = "Item price can't be <strong>empty</strong>";
        }
        if (empty($made_in)) {
          $errors[] = "Country of made can't be <strong>empty</strong>";
        }
        if (empty($status)) {
          $errors[] = "Must Choose status";
        }
        if (empty($owner)) {
          $errors[] = "Must Choose the owner";
        }
        if (empty($cat)) {
          $errors[] = "Must Choose category";
        }
        if ($errors) {
          redirect_home($errors, "back", "danger");
        }else {
          $stmt = $con->prepare("INSERT 
          INTO items(name, description, price, made_in, status, cat_id, user_id, add_date)
          VALUES(?, ?, ?, ?, ?, ?, ?, now())");
          $stmt->execute([$name, $description, $price, $made_in, $status, $cat, $owner]);
          $rows = $stmt->rowCount();
          if ($rows):
            redirect_home([lang("ITEMADDED")], "back", "success");
          else:
            redirect_home([lang("NOITEMADDED")], "back", "danger");
          endif;
        }
      }else {
        redirect_home([lang("CANTBROWSE")], "back", "danger");
      }
    }elseif ($action == "edit") {
      
    }elseif ($action == "update") {
      
    }elseif ($action == "delete") {
      
    }elseif ($action == "approve") {
      
    }
    include($tpl . "footer.php");
  }else {
    header("location: index.php");
    exit();
  }