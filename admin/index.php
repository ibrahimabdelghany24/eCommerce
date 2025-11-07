<?php
  session_start();
  $page_title = "Login";
  if (isset($_SESSION["username"])) {
    header("location: dashboard.php");
  }
  include("init.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    // check if user exist
    $stmt = $con->prepare("SELECT userid, username, password
                          FROM users WHERE username = ? AND groupid = 1 LIMIT 1");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      if (password_verify($password, $row["password"]))
      $_SESSION["username"] = $username;
      $_SESSION["userid"] = $row["userid"];
      header("location: dashboard.php");
      exit();
    }
  }
?>

  <form class="login form" action="index.php" method="POST">
    <h2><?php echo lang("ADMINLOGIN") ?></h2>
    <div>
      <i class="fa-solid fa-user user"></i>
      <input class="form-control" type="text" name="username"
        placeholder="Username" data-text="Username" autocomplete="off">
    </div>
    <div>
      <i class="fa-solid fa-key key"></i>
      <input class="form-control" type="password" name="password"
        placeholder="Password" data-text="Password" autocomplete="off" >
      <i class="fa-solid fa-eye-slash eye"></i>
    </div> 
    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Login" >
  </form>

<?php include($tpl . "footer.php");?>