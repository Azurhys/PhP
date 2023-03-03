<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  require_once '../_inc/functions.php';

  include('../_inc/header.php');
  include('../admin/_inc/nav.php');
  checkAuthentication();
?>

<a href="/admin/games/index.php">INDEX GAMES ADMIN</a>

<?php
include('../_inc/footer.php');
?>