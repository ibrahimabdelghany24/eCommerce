<?php 
  session_start();
  if (isset($_SESSION["username"])){
    $navbar = true;
    $page_title = "Dashboard";
    include("init.php");?>
    <div class="container text-center home-stats">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            Total Members
            <span><a href="members.php"><?php echo count_item("userid", "users") ?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">
            Pending Members
            <span><a href="members.php?action=manage&page=pending"><?php echo count_item("userid", "users", "WHERE reg_status = 0") ?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">
            Total Items
            <span>1500</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">
            Total Comments
            <span>3500</span>
          </div>
        </div>
      </div>
    </div>
    <div class="container latest">
      <div class="row">
        <div class="col-lg-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="fa-solid fa-users"></i> Latest registered users
            </div>
            <div class="panel-body">
              test
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="fa-solid fa-tag"></i> Latest items
            </div>
            <div class="panel-body">
              test
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
    include($tpl . "footer.php");
  } else{
    header("location: index.php");
    exit();
  }
