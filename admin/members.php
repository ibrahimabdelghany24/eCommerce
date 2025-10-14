<?php
session_start();
$navbar = true;
$page_title = "Members";
if (isset($_SESSION["username"])) {
  include("init.php");
  $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";

  if ($action == "manage") { // manage page
    echo "Manage page";
    echo "<a href=?action=add>Add Members</a>";
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
          echo "There is no such ID";
        }
  // edit end
  } elseif ($action == "update") { // update page
    echo "<h1 class='text-center'>Update Data</h1>";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
      foreach ($errors as $error) :
        echo "<h3 class='error' >$error</h3>";
      endforeach;
      if (empty($errors)) :
        if (empty($password)) {
          $stmt = $con->prepare("UPDATE users
                                    SET
                                    username = ?, email = ?, fullname = ?
                                    WHERE userid = ?");
          $stmt->execute([$username, $email, $name, $userid]);
        } else {
          $hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $con->prepare("UPDATE users
                                  SET 
                                  username = ?, email = ?, fullname = ?, password = ?
                                  WHERE userid = ?");
          $stmt->execute([$username, $email, $name, $hashed, $userid]);
        }
      endif;
      echo "<h3 class='success'>" . $stmt->rowCount() . " record updated</h3>";
    } else {
      echo "Sorry, You can't browse this page";
    }
  // update end
  } elseif ($action == "add") { // add page
    $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
    ?>
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
    echo "<h1 class='text-center'>Insert Page</h1>";
    echo $_POST["username"] . " " . $_POST["password"] . " " . $_POST["email"] . " " . $_POST["fullname"];
  }
  include($tpl . "footer.php");
} else {
  header("location: index.php");
  exit();
}