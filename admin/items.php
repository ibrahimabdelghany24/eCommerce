<?php 
  session_start();
  $page_title = "Items";
  $navbar = true;
  if (isset($_SESSION["userid"])) {
    include("init.php");
    $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
    if ($action == "manage") {
      echo "<h1 class='text-center'>Item Page" . "</h1>";
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
                required>
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
              <select class="" name="status">
                <option value="1"><?php echo lang("NEW") ?></option>
                <option value="2"><?php echo lang("LIKENEW") ?></option>
                <option value="3"><?php echo lang("USED") ?></option>
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