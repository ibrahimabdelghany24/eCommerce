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
    <h1 class="text-center">Manage Members</h1>
    <div class="container">
      <div class="table-responsive">
        <table class="table text-center main-table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Full Name</th>
              <th>Regester Date</th>
              <th>Control</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach($rows as $row):
              echo "<tr>";
              echo "<td>" . $row["userid"] ."</td>";
              echo "<td>" . $row["username"] ."</td>";
              echo "<td>" . $row["email"] ."</td>";
              echo "<td>" . $row["fullname"] ."</td>";
              echo "<td>" . $row["date"] ."</td>";
              echo "<td>
              <a href='?action=edit&userid={$row["userid"]}' class='btn btn-success'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
              <form method='POST' action='?action=delete' style='display:inline;'>
              <input type='hidden' name='userid' value='{$row["userid"]}'>
              <button type='submit' class='btn btn-danger confirm'>
              <i class='fa-solid fa-trash'></i> Delete
              </button>
              </form>";
              if (!$row["reg_status"]):
              echo "<a href='?action=activate&userid={$row["userid"]}' class='btn btn-primary'><i class='fa-solid fa-square-check'></i> Activate</a>";
              endif;
              echo "</td>";
              echo "<tr>";
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
      <a href="?action=add" class="btn btn-primary"> <i class="fa fa-plus"></i> New Member</a>
    </div>
  <?php
  // manage end
  } elseif ($action == "edit") { // edit page
    $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
    $stmt->execute([$userid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) { ?>
      <h1 class="text-center">Edit Member</h1>
      <div class="container">
        <form class="edit form" action="?action=update" method="POST" autocomplete="off">
          <input style="display: hidden" type="hidden" name="userid" value=<?php echo $row["userid"] ?> autocomplete="off">
          <div>
            <i class="fa-solid fa-user user"></i>
            <input class="form-control" type="text" name="username" value="<?php echo $row["username"] ?>" placeholder="Username" data-text="Username" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-key key"></i>
            <input class="form-control" type="password" name="password" placeholder="New password if you want" data-text="Password" autocomplete="new-password">
          </div>
          <div>
            <i class="fa-solid fa-at"></i>
            <input class="form-control" type="email" name="email" value="<?php echo $row["email"] ?>" placeholder="Email" data-text="Email" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-id-card"></i>
            <input class="form-control" type="text" name="fullname" value="<?php echo $row["fullname"] ?>" placeholder="Full Name" data-text="Full Name" autocomplete="off" required>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="Save">
        </form>
      </div>
    <?php
    } else {
          redirect_home(["There is no such ID"], "back", "danger");
        }
  // edit end
  } elseif ($action == "update") { // update page
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      echo "<h1 class='text-center'>Update Data</h1>";
      $userid = $_POST["userid"];
      $username = $_POST["username"];
      $email = $_POST["email"];
      $name = $_POST["fullname"];
      $password = $_POST["password"];
      $errors = array();
      if (strlen($username) < 4) {
        $errors[] = "Username can't be less than <strong>3</strong> characters";
      }
      if (strlen($username) > 20) {
        $errors[] = "Username can't be more than <strong>20</strong> characters";
      }
      if (empty($username)) {
        $errors[] = "Username can't be <strong>empty</strong>";
      }
      if (empty($name)) {
        $errors[] = "Full name can't be <strong>empty</strong>";
      }
      if (empty($email)) {
        $errors[] = "Email can't be <strong>empty</strong>";
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
      $msg = [$stmt->rowCount() . " record updated"];
      redirect_home($msg, "back", "success");
    } else {
      redirect_home(["You can't browse this page directly"], "back", "danger");
    }
  // update end
  } elseif ($action == "add") { // add page?>
      <h1 class="text-center">Add Member</h1>
      <div class="container">
        <form class="Add form" action="?action=insert" method="POST" autocomplete="off">
          <div>
            <i class="fa-solid fa-user user"></i>
            <input class="form-control" type="text" name="username" placeholder="Username" data-text="Username" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-key key"></i>
            <input class="form-control" type="password" name="password" placeholder="Password" data-text="Password" autocomplete="new-password" required>
            <i class="fa-solid fa-eye-slash eye"></i>
          </div>
          <div>
            <i class="fa-solid fa-at"></i>
            <input class="form-control" type="email" name="email" placeholder="Email" data-text="Email" autocomplete="off" required>
          </div>
          <div>
            <i class="fa-solid fa-id-card"></i>
            <input class="form-control" type="text" name="fullname" placeholder="Full Name" data-text="Full Name" autocomplete="off" required>
          </div>
          <input class="btn btn-primary btn-block" type="submit" name="submit" value="Add Member">
        </form>
      </div>
      <?php
  // add end
  }elseif ($action == "insert") { // insert page
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      echo "<h1 class='text-center'>Insert User</h1>";
      $username = $_POST["username"];
      $email = $_POST["email"];
      $name = $_POST["fullname"];
      $password = $_POST["password"];
      $errors = array();
      if (strlen($username) < 4) {
        $errors[] = "Username can't be less than <strong>3</strong> characters";
      }
      if (strlen($username) > 20) {
        $errors[] = "Username can't be more than <strong>20</strong> characters";
      }
      if (empty($username)) {
        $errors[] = "Username can't be <strong>empty</strong>";
      }
      if (strlen($password) < 8) {
        $errors[] = "Password can't be less than <strong>8</strong> character";
      }
      if (empty($name)) {
        $errors[] = "Full name can't be <strong>empty</strong>";
      }
      if (empty($email)) {
        $errors[] = "Email can't be <strong>empty</strong>";
      }
      if (empty($errors)) :
        if (!is_exist("username", "users", $username)):
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $con->prepare("INSERT INTO 
                                  users(username, password, email, fullname, reg_status, date)
                                  VALUES(?, ?, ?, ?, 1, now())");
          $stmt->execute([$username, $hashed, $email, $name]);
          $msg = [$stmt->rowCount() . " User added"];
          redirect_home($msg, "back", "success");
        else:
          $msg = ["{$username} is already exist", "No Users added"];
          redirect_home($msg, "back", "danger");
        endif;
      else:
        redirect_home($errors, "back", "danger");
      endif;
    } else {
      redirect_home(["You can't browse this page directly"], "back", "danger");
    }
  // end insert
  }elseif ($action == "delete") { // delete page
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
      echo "<h1 class='text-center'>Delete User</h1>";
      $row = 0;
      $userid = (isset($_POST["userid"]) && is_numeric($_POST["userid"])) ? intval($_POST["userid"]) : 0;
      if (is_exist("userid", "users", $userid)):
        $stmt = $con->prepare("DELETE FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute([$userid]);
        $row = $stmt->rowCount();
        $msg = [$row . " User deleted"];
        redirect_home($msg, "back", "success");
      else:
        $msg = "There is no such ID";
        redirect_home($msg, "back", "danger");
      endif;
    else:
      redirect_home(["You can't browse this page directly"], "back", "danger");
    endif;
  // end delete
  }
  include($tpl . "footer.php");
} else {
  header("location: index.php");
  exit();
}