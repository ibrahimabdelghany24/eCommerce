<?php 
  session_start();
  $page_title = "Comments";
  $navbar = true;
  if (isset($_SESSION["userid"])) {
    include("init.php");
    $action = (isset($_GET["action"])) ? $_GET["action"] : "manage";
    if ($action == "manage") { // manage page
      $stmt = $con->prepare(
        "SELECT
          comments.* ,users.username AS user_name, items.name AS item_name
        FROM comments

        INNER JOIN users
        ON users.userid = comments.user_id

        INNER JOIN items
        ON items.id = comments.item_id
        ORDER BY comments.comment_date");
      $stmt->execute();
      $rows = $stmt->fetchAll();
      ?>
      <h1 class="text-center"><?=lang("MANAGECOMMENTS") ?></h1>
      <div class="container">
        <div class="table-responsive">
          <table class="table text-center main-table table-hover table-bordered">
            <thead>
              <tr>
                <th><?=lang("ID")?></th>
                <th><?=lang("COMMENT")?></th>
                <th><?=lang("USERNAME")?></th>
                <th><?=lang("ITEMNAME")?></th>
                <th><?=lang("ADDDATE")?></th>
                <th><?=lang("CONTROL")?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($rows as $row):
                echo "<tr class='active'>";
                echo "<td>" . $row["c_id"] ."</td>";
                echo "<td>" . $row["comment"] ."</td>";
                echo "<td>" . $row["user_name"] ."</td>";
                echo "<td>" . $row["item_name"] ."</td>";
                echo "<td>" . $row["comment_date"] ."</td>";
                echo "<td>
                <form method='POST' action='?action=delete' style='display:inline;'>
                <input type='hidden' name='comment_id' value='{$row["c_id"]}'>
                <button type='submit' class='btn btn-danger confirm'>
                <i class='fa-solid fa-trash'></i> ". lang("DELETE") ."
                </button>
                </form>";
                if (!$row["status"]):
                echo "<a href='?action=approve&comment_id={$row["c_id"]}' class='btn btn-primary'><i class='fa-solid fa-square-check'></i> " . lang("APPROVE") . "</a>";
                endif;
                echo "</td>";
                echo "<tr>";
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php
    // end manage
    }elseif ($action == "delete") { // delete page
      if ($_SERVER["REQUEST_METHOD"] == "POST"):
        echo "<h1 class='text-center'>". lang("DELETECOMMENT") ."</h1>";
        $comment_id = (isset($_POST["comment_id"]) && is_numeric($_POST["comment_id"])) ? intval($_POST["comment_id"]) : 0;
        if (is_exist("c_id", "comments", $comment_id)):
          $stmt = $con->prepare("DELETE FROM comments WHERE c_id = ? LIMIT 1");
          $stmt->execute([$comment_id]);
          $msg = [lang("COMMENTDELETED")];
          redirect_home($msg, "back", "success");
        else:
          $msg = [lang("NOID")];
          redirect_home($msg, "back", "danger", false);
        endif;
      else:
        redirect_home([lang("CANTBROWSE")], "back", "danger", false);
      endif;
    // end delete
    }elseif ($action == "approve") { // approve page
      echo "<h1 class='text-center'>". lang("APPROVECOMMENT") ."</h1>";
      $comment_id = (isset($_GET["comment_id"]) && is_numeric($_GET["comment_id"])) ? intval($_GET["comment_id"]) : 0;
      if (is_exist("c_id", "comments", $comment_id)):
        $stmt = $con->prepare("UPDATE comments SET status = ? WHERE c_id = ?");
        $stmt->execute([1, $comment_id]);
        $msg = [lang("COMMENTAPPROVED")];
        redirect_home($msg, "back", "success");
      else:
        $msg = [lang("NOITEM")];
        redirect_home($msg, "back", "danger", false);
      endif;
    }// end approve
    include($tpl . "footer.php");
  }else {
    header("location: index.php");
    exit();
  }
?>