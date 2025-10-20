<?php 
  session_start();
  if (isset($_SESSION["username"])){
    $navbar = true;
    $page_title = "Dashboard";
    include("init.php");?>
    <div class="container text-center home-stats">
      <h1><?php echo lang("DASHBOARD") ?></h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            <?php echo lang("TOTALMEMBERS") ?>
            <span><a href="members.php"><?php echo count_item("userid", "users") ?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">
            <?php echo lang("PENDINGMEMBERS") ?>
            <span><a href="members.php?action=manage&page=pending"><?php echo count_item("reg_status", "users", "WHERE reg_status = 0") ?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">
            <?php echo lang("TOTALITEMS") ?>
            <span>1500</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">
            <?php echo lang("TOTALCOMMENTS") ?>
            <span>3500</span>
          </div>
        </div>
      </div>
    </div>
    <div class="container latest">
      <div class="row">
        <div class="col-lg-6">
          <div class="panel panel-default">
            <?php $latest = 6;?>
            <div class="panel-heading">
              <i class="fa-solid fa-users"></i> <?php echo $latest . " " . lang("LATESTMEMBERS")?>
            </div>
            <div class="panel-body">
              <ul class="list-unstyled latest-users">
                <?php
                  $data = get_latest("*", "users", "date", $latest);
                  foreach ($data as $user):
                    echo "<li>{$user["username"]}
                    <span>
                    <a class='btn btn-success' href='members.php?action=edit&userid={$user['userid']}'>
                    <i class='fa-solid fa-edit'></i> ";
                    echo lang("EDIT");
                    echo "</a>";
                    if (!$user["reg_status"]):
                      echo "<a href='members.php?action=activate&userid={$user["userid"]}' class='btn btn-primary' style='margin-left:5px;'><i class='fa-solid fa-square-check'></i> Activate</a>";
                    endif;
                    echo "</span>
                    </li>";
                  endforeach;
                  ?>
              </ul>
              <hr style="margin:0;">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="fa-solid fa-tag"></i> <?php echo lang("LATESTITEMS") ?>
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
