<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand active" href="dashboard.php"><?= lang("HOME")?></a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav links">
        <li><a href="categories.php"><?= lang("CATEGORIES") ?></a></li>
        <li><a href="items.php"><?= lang("ITEMS") ?></a></li>
        <li><a href="members.php"><?= lang("MEMBERS") ?></a></li>
        <li><a href="comments.php"><?= lang("COMMENTS") ?></a></li>
        <li><a href="#"><?= lang("STATISTICS") ?></a></li>
        <li><a href="#"><?= lang("LOGS") ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $_SESSION["username"] ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="members.php?action=edit&userid=<?= $_SESSION['userid'] ?>"><?= lang("EDITPROFILE") ?></a></li>
            <li><a href="#"><?= lang("SETTINGS") ?></a></li>
            <li><a href="logout.php"><?= lang("LOGOUT") ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<script>
  const navLinks = document.querySelectorAll("nav.navbar #app-nav .links li a");
  const brandLink = document.querySelector(".navbar-brand")
  if (navLinks) {
    navLinks.forEach(link => {
      if (link.href === window.location.href) {
        link.classList.add("active");
        brandLink.classList.remove("active");
      }
    });
  }
</script>