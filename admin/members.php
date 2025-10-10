<?php 
  session_start();
  $navbar = true;
  $page_title = "Members";
  if (isset($_SESSION["username"])){
    include("init.php");
    $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";

    if ($action == "manage") { // manage page
      echo "Manage page";

    }elseif ($action == "edit") { // edit page
      $userid = (isset($_GET["userid"]) && is_numeric($_GET["userid"])) ? intval($_GET["userid"]) : 0;
      $stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
      $stmt->execute([$userid]);
      $row = $stmt->fetch();
      if ($row) { ?>
        <h1 class="text-center">Edit Member</h1>
        <div class="container">
          <form class="edit" action="?action=update" method="POST" autocomplete="off">
            <div>
              <input type="hidden" name="userid" value=<?php echo $row["userid"]?> autocomplete="off">
            </div>
            <div>
              <i class="fa-solid fa-user user"></i>
              <input class="form-control" type="text" name="username" value="<?php echo $row["username"]?>" placeholder="Username" data-text="Username" autocomplete="off">
            </div>
            <div>
              <i class="fa-solid fa-key key"></i>
              <input class="form-control" type="password" name="password" placeholder="New Password" data-text="Password" autocomplete="new-password" >
            </div> 
            <div>
              <i class="fa-solid fa-at"></i>
              <input class="form-control" type="email" name="email" value="<?php echo $row["email"]?>" placeholder="Email" data-text="Email" autocomplete="off">
            </div>
            <div>
              <i class="fa-solid fa-id-card"></i>
              <input class="form-control" type="text" name="fullname" value="<?php echo $row["fullname"]?>" placeholder="Full Name" data-text="Full Name" autocomplete="off" >
            </div> 
            <input class="btn btn-primary btn-block" type="submit" name="submit" value="Save" >
          </form>
        </div>
      <?php
      }else {
        echo "There is no such ID";
      }
      ?>
<?php
    }elseif ($action == "update") {
      echo "<h1 class='text-center'>Update Data</h1>";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userid = $_POST["userid"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $name = $_POST["fullname"];
        $stmt = $con->prepare("UPDATE users SET username = ?, email = ?, fullname = ? WHERE userid = ?");
        $stmt->execute([$username, $email, $name, $userid]);
      }else {
        echo "Sorry, You can't browse this page";
      }
    }
    include($tpl . "footer.php");
  } else {
    header("location: index.php");
    exit();
  }