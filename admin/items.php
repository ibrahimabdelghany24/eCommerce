<?php 
  session_start();
  $page_title = "Items";
  $navbar = true;
  if (isset($_SESSION["userid"])) {
    include("init.php");
    $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
    if ($action == "manage") { // manage page
      $stmt = $con->prepare(
      " SELECT 
          items.* , 
          categories.name AS cat_name,
          users.username
        FROM 
          items
        INNER JOIN
          categories ON categories.id = items.cat_id
        INNER JOIN 
          users ON users.userid = items.user_id");
      $stmt->execute();
      $items = $stmt->fetchAll();
      ?>
      <h1 class="text-center"><?=lang("MANAGEITEMS") ?></h1>
      <div class="container">
        <div class="table-responsive">
          <table class="table text-center main-table table-hover table-bordered">
            <thead>
              <tr>
                <th><?=lang("ID")?></th>
                <th><?=lang("ITEMNAME")?></th>
                <th><?=lang("ITEMDESCRIPTION")?></th>
                <th><?=lang("ITEMPRICE")?></th>
                <th><?=lang("ADDDATE")?></th>
                <th><?=lang("CATEGORY")?></th>
                <th><?=lang("USERNAME")?></th>
                <th><?=lang("CONTROL")?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($items as $item):
                echo "<tr class='active'>";
                echo "<td>" . $item["id"] ."</td>";
                echo "<td>" . $item["name"] ."</td>";
                echo "<td>" . $item["description"] ."</td>";
                echo "<td>" . $item["price"] ."</td>";
                echo "<td>" . $item["add_date"] ."</td>";
                echo "<td>" . $item["cat_name"] ."</td>";
                echo "<td>" . $item["username"] ."</td>";
                echo "<td>
                <a href='?action=edit&item_id={$item["id"]}' class='btn btn-success'><i class='fa-solid fa-pen-to-square'></i>". lang("EDIT") . "</a>
                <form method='POST' action='?action=delete' style='display:inline;'>
                <input type='hidden' name='userid' value='{$item["id"]}'>
                <button type='submit' class='btn btn-danger confirm'>
                <i class='fa-solid fa-trash'></i> ". lang("DELETE") ."
                </button>
                </form>";
                echo "</td>";
                echo "<tr>";
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
        <a href="?action=add" class="btn btn-primary">
          <i class="fa fa-plus"></i><?=lang("ADDITEM")?>
        </a>
      </div>
    <?php
    // end manage
    }elseif ($action == "add") { // add page?>
      <h1 class="text-center"><?=lang("ADDITEM") ?></h1>
      <div class="container">
        <form class="add-item form" action="?action=insert" method="POST">
          <div>
            <label class="feild">
              <span><?=lang("ITEMNAME")?></span>
              <input 
                class="form-control" 
                type="text" 
                name="name" 
                placeholder="<?=lang("ITEMNAME")?>"
                data-text="<?=lang("ITEMNAME")?>" 
                >
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?=lang("ITEMDESCRIPTION")?></span>
              <input 
                class="form-control" 
                type="text" name="description" 
                placeholder="<?=lang("ITEMDESCRIPTION")?>" 
                data-text="<?=lang("ITEMDESCRIPTION")?>">
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?=lang("ITEMPRICE")?></span>
              <input 
                class="form-control" 
                type="text" name="price" 
                placeholder="<?=lang("ITEMPRICE")?>" 
                data-text="<?=lang("ITEMPRICE")?>">
            </label>
          </div>
          <div>
            <label class="feild">
              <span><?=lang("MADEIN")?></span>
              <input 
                class="form-control" 
                type="text" name="madein" 
                placeholder="<?=lang("MADEIN")?>" 
                data-text="<?=lang("MADEIN")?>">
            </label>
          </div>
          <div>
            <label class="feild status">
              <span><?=lang("ITEMSTATUS")?></span>
              <select name="status">
                <option value="" selected disabled><?=lang("SELECTSTATUS")?></option>
                <option value="1"><?=lang("NEW") ?></option>
                <option value="2"><?=lang("LIKENEW") ?></option>
                <option value="3"><?=lang("USED") ?></option>
              </select>
            </label>
          </div>
          <div>
            <label class="feild status">
              <span><?=lang("ITEMOWNER")?></span>
              <select name="owner">
                <option value="" selected disabled><?=lang("SELECTUSER")?></option>
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
                <option value="" selected disabled><?=lang("SELECTCAT")?></option>
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
          value="<?=lang("ADDITEM")?>">
        </form>
      </div>
      <?php
    // end add
    }elseif ($action == "insert") { // insert page
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
          $errors[] = lang("ITEMNAMEEMPTY");
        }
        if (empty($description)) {
          $errors[] = lang("ITEMDESCEMPTY");
        }
        if (empty($price)) {
          $errors[] = lang("ITEMPRICEEMPTY");
        }
        if (empty($made_in)) {
          $errors[] = lang("ITECOUNTRYEMPTY");
        }
        if (empty($status)) {
          $errors[] = lang("MUSTSTATUS");
        }
        if (empty($owner)) {
          $errors[] = lang("MUSTOWNER");
        }
        if (empty($cat)) {
          $errors[] = lang("MUSTCAT");
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
            redirect_home([lang("ITEMADDED")], "items.php", "success");
          else:
            redirect_home([lang("NOITEMADDED")], "back", "danger");
          endif;
        }
      }else {
        redirect_home([lang("CANTBROWSE")], "back", "danger");
      }
    // end insert
    }elseif ($action == "edit") { // edit page
      $item_id = (isset($_GET["item_id"]) && is_numeric($_GET["item_id"])) ? intval($_GET["item_id"]): 0;
      if (is_exist("id", "items", $item_id)) {
        $stmt = $con->prepare("SELECT * from items WHERE id = ?");
        $stmt->execute([$item_id]);
        $item = $stmt->fetch();
        $count = $stmt->rowCount();?>
        <h1 class="text-center"><?=lang("EDITITEM") ?></h1>
        <div class="container">
          <form class="add-item form" action="?action=update" method="POST">
            <input class="hidden" type="text" name="id" value=<?=$item["id"]?>>
            <div>
              <label class="feild">
                <span><?=lang("ITEMNAME")?></span>
                <input 
                  class="form-control" 
                  type="text" 
                  name="name" 
                  value="<?=$item["name"]?>"
                  placeholder="<?=lang("ITEMNAME")?>"
                  data-text="<?=lang("ITEMNAME")?>" 
                  >
              </label>
            </div>
            <div>
              <label class="feild">
                <span><?=lang("ITEMDESCRIPTION")?></span>
                <input 
                  class="form-control" 
                  type="text" name="description"
                  value="<?=$item["description"]?>"
                  placeholder="<?=lang("ITEMDESCRIPTION")?>" 
                  data-text="<?=lang("ITEMDESCRIPTION")?>">
              </label>
            </div>
            <div>
              <label class="feild">
                <span><?=lang("ITEMPRICE")?></span>
                <input 
                  class="form-control" 
                  type="text" name="price" 
                  value="<?=$item["price"]?>"
                  placeholder="<?=lang("ITEMPRICE")?>" 
                  data-text="<?=lang("ITEMPRICE")?>">
              </label>
            </div>
            <div>
              <label class="feild">
                <span><?=lang("MADEIN")?></span>
                <input 
                  class="form-control" 
                  type="text" name="madein"
                  value="<?=$item["made_in"]?>" 
                  placeholder="<?=lang("MADEIN")?>" 
                  data-text="<?=lang("MADEIN")?>">
              </label>
            </div>
            <div>
              <label class="feild status">
                <span><?=lang("ITEMSTATUS")?></span>
                <select name="status">
                  <option value="" disabled><?=lang("SELECTSTATUS")?></option>
                  <option value="1" <?=($item["status"] == 1) ? "selected" : "";?>><?=lang("NEW") ?></option>
                  <option value="2" <?=($item["status"] == 2) ? "selected" : "";?>><?=lang("LIKENEW") ?></option>
                  <option value="3" <?=($item["status"] == 3) ? "selected" : "";?>><?=lang("USED") ?></option>
                </select>
              </label>
            </div>
            <div>
              <label class="feild status">
                <span><?=lang("ITEMOWNER")?></span>
                <select name="owner">
                  <option value="" disabled><?=lang("SELECTUSER")?></option>
                  <?php
                    $stmt = $con->prepare("SELECT userid, username FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach($users as $user):
                      echo "<option value='{$user["userid"]}'" . (($user["userid"] == $item["user_id"]) ? "selected" : "") .">{$user["username"]}</option>";
                    endforeach;
                  ?>
                </select>
              </label>
            </div>
            <div>
              <label class="feild status">
                <span><?=lang("ITEMCAT")?></span>
                <select name="cat">
                  <option value="" disabled><?=lang("SELECTCAT")?></option>
                  <?php
                    $stmt = $con->prepare("SELECT id, name FROM categories");
                    $stmt->execute();
                    $cats = $stmt->fetchAll();
                    foreach($cats as $cat):
                      echo "<option value='{$cat["id"]}'" . (($cat["id"] == $item["cat_id"])? "selected": "") . ">{$cat["name"]}</option>";
                    endforeach;
                  ?>
                </select>
              </label>
            </div>
            <input class="btn btn-primary btn-block"
            type="submit" name="submit" 
            value="<?=lang("EDITITEM")?>">
          </form>
        </div>
        <?php
      }else {
        redirect_home([lang("NOITEM")], "items.php", "danger");
      }
    // end edit
    }elseif ($action == "update") {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h1 class='text-center'>" . lang('UPDATEITEM') ."</h1>";
        $item_id = $_POST["id"];
        $item_name = $_POST["name"];
        $item_desc = $_POST["description"];
        $item_price = $_POST["price"];
        $made_in = $_POST["madein"];
        $status = $_POST["status"];
        $item_owner = $_POST["owner"];
        $item_cat = $_POST["cat"];
        $errors = [];
        if (empty($item_name)) {
          $errors[] = lang("ITEMNAMEEMPTY");
        }
        if (empty($item_desc)) {
          $errors[] = lang("ITEMDESCEMPTY");
        }
        if (empty($item_price)) {
          $errors[] = lang("ITEMPRICEEMPTY");
        }
        if (empty($made_in)) {
          $errors[] = lang("ITECOUNTRYEMPTY");
        }
        if (empty($status)) {
          $errors[] = lang("MUSTSTATUS");
        }
        if (empty($item_owner)) {
          $errors[] = lang("MUSTOWNER");
        }
        if (empty($item_cat)) {
          $errors[] = lang("MUSTCAT");
        }
        if ($errors) {
          redirect_home($errors, "back", "danger");
        }
        if (empty($errors)) {
          $stmt = $con->prepare("UPDATE items 
          SET 
          name = ?, description = ?, price = ?, made_in = ?, status = ?, cat_id = ?, user_id = ?
          where id = ?");
          $stmt->execute([$item_name, $item_desc, $item_price, $made_in, $status, $item_cat, $item_owner, $item_id]);
          $count = $stmt->rowCount();
          if ($count):
            redirect_home([lang("ITEMUPDATED")], "back", "success", false);
          else:
            redirect_home([lang("NOITEMUPDATED")], "back", "danger", false);
          endif;
        }
      }
    }elseif ($action == "delete") {
      
    }elseif ($action == "approve") {
      
    }
    include($tpl . "footer.php");
  }else {
    header("location: index.php");
    exit();
  }