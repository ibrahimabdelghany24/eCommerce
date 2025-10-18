<?php
session_start();
$navbar = true;
$page_title = "Members";
if (isset($_SESSION["username"])) {
  include("init.php");
  $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
  if ($action == "manage") { // manage page
    $query = (isset($_GET["page"]) && $_GET["page"] == "pending") ? "AND reg_status = 0" : "";
    $stmt = $con->prepare("SELECT * FROM users WHERE groupid != 1 {$query}");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    ?>
    <h1 class="text-center"><?php echo lang("MANAGEUSERS") ?></h1>
    <div class="container">
      <div class="table-responsive">
        <table class="table text-center main-table table-hover table-bordered">
          <thead>
            <tr>
              <th><?php echo lang("ID")?></th>
              <th><?php echo lang("USERNAME")?></th>
              <th><?php echo lang("EMAIL")?></th>
              <th><?php echo lang("FULLNAME")?></th>
              <th><?php echo lang("REGISTERDATE")?></th>
              <th><?php echo lang("CONTROL")?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach($rows as $row):
              echo "<tr class='active'>";
              echo "<td>" . $row["userid"] ."</td>";
              echo "<td>" . $row["username"] ."</td>";
              echo "<td>" . $row["email"] ."</td>";
              echo "<td>" . $row["fullname"] ."</td>";
              echo "<td>" . $row["date"] ."</td>";
              echo "<td>
              <a href='?action=edit&userid={$row["userid"]}' class='btn btn-success'><i class='fa-solid fa-pen-to-square'></i>". lang("EDIT") . "</a>
              <form method='POST' action='?action=delete' style='display:inline;'>
              <input type='hidden' name='userid' value='{$row["userid"]}'>
              <button type='submit' class='btn btn-danger confirm'>
              <i class='fa-solid fa-trash'></i> ". lang("DELETE") ."
              </button>
              </form>";
              if (!$row["reg_status"]):
              echo "<a href='?action=activate&userid={$row["userid"]}' class='btn btn-primary'><i class='fa-solid fa-square-check'></i> " . lang("ACTIVATE") . "</a>";
              endif;
              echo "</td>";
              echo "<tr>";
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
      <a href="?action=add" class="btn btn-primary"> <i class="fa fa-plus"></i><?php echo " " . lang("NEW") . " " . lang("USER") ?></a>
    </div>
  <?php
  // manage end
  } elseif ($action == "edit") { // edit page
    $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
    $stmt->execute([$userid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) { ?>
      <h1 class="text-center"><?php echo lang("EDITUSER") ?></h1>
      <div class="container">
        <form class="edit form" action="?action=update" method="POST" autocomplete="off">
          <input type="hidden" name="userid" value=<?php echo $row["userid"] ?> autocomplete="off">
          <div>
            <i class="fa-solid fa-user user"></i>
            <input class="form-control" type="text" name="username" value="<?php echo $row["username"] ?>" placeholder="<?php echo lang("USERNAME") ?>" data-text="<?php echo lang("USERNAME") ?>" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-key key"></i>
            <input class="form-control" type="password" name="password" placeholder="<?php echo lang("NEWPASSWORD")?>"  data-text="<?php echo lang("NEWPASSWORD")?>" autocomplete="new-password">
          </div>
          <div>
            <i class="fa-solid fa-at"></i>
            <input class="form-control" type="email" name="email" value="<?php echo $row["email"] ?>" placeholder="<?php echo lang("EMAIL")?>" data-text="<?php echo lang("EMAIL")?>" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-id-card"></i>
            <input class="form-control" type="text" name="fullname" value="<?php echo $row["fullname"] ?>" placeholder="<?php echo lang("FULLNAME") ?>" data-text="<?php echo lang("FULLNAME") ?>" autocomplete="off" required>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="<?php echo lang("SAVE") ?>">
        </form>
      </div>
    <?php
    } else {
          redirect_home([lang("NOID")], "back", "danger");
        }
  // edit end
  } elseif ($action == "update") { // update page
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      echo "<h1 class='text-center'>" . lang("UPDATEUSER") . "</h1>";
      $userid = $_POST["userid"];
      $username = $_POST["username"];
      $email = $_POST["email"];
      $name = $_POST["fullname"];
      $password = $_POST["password"];
      $errors = array();
      if (strlen($username) < 4) {
        $errors[] = lang("USERNAMELESS");
      }
      if (strlen($username) > 20) {
        $errors[] = lang("USERNAMEMORE");
      }
      if (empty($username)) {
        $errors[] = lang("USERNAMEEMPTY");
      }
      if (empty($name)) {
        $errors[] = lang("FULLNAMEEMPTY");
      }
      if (empty($email)) {
        $errors[] = lang("EMAILEMPTY");
      }
      if(!empty($password) && strlen($password) < 8) {
        $errors[] = lang("PASSWORDLESS");
      }
      if (empty($errors)) :
        if (empty($password)) {
          $stmt = $con->prepare("UPDATE users
                                    SET
                                    username = ?, email = ?, fullname = ?
                                    WHERE 
                                    userid = ?");
          $stmt->execute([$username, $email, $name, $userid]);
        } else {
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $con->prepare("UPDATE users
                                  SET 
                                  username = ?, email = ?, fullname = ?, password = ?
                                  WHERE
                                  userid = ?");
          $stmt->execute([$username, $email, $name, $hashed, $userid]);
        }
      else:
        redirect_home($errors, "back", "danger");
      endif;
      $msg = [$stmt->rowCount() . " " . lang("USERUPDATED")];
      redirect_home($msg, "back", "success");
    } else {
      redirect_home([lang("CANTBROWSE")], "back", "danger");
    }
  // update end
  } elseif ($action == "add") { // add page?>
      <h1 class="text-center"><?php echo lang("ADDUSER") ?></h1>
      <div class="container">
        <form class="Add form" action="?action=insert" method="POST" autocomplete="off">
          <div>
            <i class="fa-solid fa-user user"></i>
            <input class="form-control" type="text" name="username" placeholder="<?php echo lang("USERNAME")?>" data-text="<?php echo lang("USERNAME")?>" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-key key"></i>
            <input class="form-control" type="password" name="password" placeholder="<?php echo lang("PASSWORD")?>" data-text="<?php echo lang("PASSWORD")?>" autocomplete="new-password" required>
            <i class="fa-solid fa-eye-slash eye"></i>
          </div>
          <div>
            <i class="fa-solid fa-at"></i>
            <input class="form-control" type="email" name="email" placeholder="<?php echo lang("EMAIL")?>" data-text="<?php echo lang("EMAIL")?>" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-id-card"></i>
            <input class="form-control" type="text" name="fullname" placeholder="<?php echo lang("FULLNAME")?>" data-text="<?php echo lang("FULLNAME")?>" autocomplete="off" required>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="<?php echo lang("ADD") . " " . lang("MEMBER")?>">
        </form>
      </div>
      <?php
  // add end
  }elseif ($action == "insert") { // insert page
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      echo "<h1 class='text-center'>" .lang("INSERTUSER"). "</h1>";
      $username = $_POST["username"];
      $email = $_POST["email"];
      $name = $_POST["fullname"];
      $password = $_POST["password"];
      $errors = array();
      if (strlen($username) < 4) {
        $errors[] = lang("USERNAMELESS");
      }
      if (strlen($username) > 20) {
        $errors[] = lang("USERNAMEMORE");
      }
      if (empty($username)) {
        $errors[] = lang("USERNAMEEMPTY");
      }
      if (strlen($password) < 8) {
        $errors[] = lang("PASSWORDLESS");
      }
      if (empty($name)) {
        $errors[] = lang("FULLNAMEEMPTY");
      }
      if (empty($email)) {
        $errors[] = lang("EMAILEMPTY");
      }
      if (empty($errors)) :
        if (!is_exist("username", "users", $username)):
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $con->prepare("INSERT INTO 
                                  users(username, password, email, fullname, reg_status, date)
                                  VALUES(?, ?, ?, ?, 1, now())");
          $stmt->execute([$username, $hashed, $email, $name]);
          $msg = [$stmt->rowCount() . " " . lang("USERADDED")];
          redirect_home($msg, "back", "success");
        else:
          $msg = [lang("USEREXIST"), lang("NOUSERADDED")];
          redirect_home($msg, "back", "danger");
        endif;
      else:
        redirect_home($errors, "back", "danger");
      endif;
    } else {
      redirect_home([lang("CANTBROWSE")], "back", "danger");
    }
  // end insert
  }elseif ($action == "delete") { // delete page
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
      echo "<h1 class='text-center'>". lang("DELETEUSER") ."</h1>";
      $row = 0;
      $userid = (isset($_POST["userid"]) && is_numeric($_POST["userid"])) ? intval($_POST["userid"]) : 0;
      if (is_exist("userid", "users", $userid)):
        $stmt = $con->prepare("DELETE FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute([$userid]);
        $row = $stmt->rowCount();
        $msg = [$row . " " . lang("USERDELETED")];
        redirect_home($msg, "back", "success");
      else:
        $msg = lang("NOID");
        redirect_home($msg, "back", "danger");
      endif;
    else:
      redirect_home([lang("CANTBROWSE")], "back", "danger");
    endif;
  // end delete
  }elseif ($action == "activate"){ // activate page
    echo "<h1 class='text-center'>". lang("ACTIVATEUSER") ."</h1>";
    $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
    if (is_exist("userid", "users", $userid)):
      $stmt = $con->prepare("UPDATE users SET reg_status = ? WHERE userid = ?");
      $stmt->execute([1, $userid]);
      $row = $stmt->rowCount();
      $msg = [$row . " " . lang("USERACTIVATED")];
      redirect_home($msg, "back", "success");
    else:
      $msg = lang("NOID");
      redirect_home($msg, "back", "danger");
    endif;
  // end activate
  }
  include($tpl . "footer.php");
} else {
  header("location: index.php");
  exit();
}