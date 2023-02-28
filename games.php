<?php
require_once './_inc/functions.php';
require_once './_inc/header.php';
require_once './_inc/nav.php';
session_start();
?>

<?php
$games = get_all_games();
?>

<div class="game-list">
  <?php foreach ($games as $game): ?>
    <div class="game">
      <h2><?= $game['title'] ?></h2>
      <img src="<?= $game['poster'] ?>" alt="<?= $game['title'] ?>" width="300" height="400">
      <p>Prix: <?= $game['price'] ?> €</p>
      <a href="game.php?id=<?= $game['id'] ?>">Consulter</a>
    </div>
  <?php endforeach ?>
</div>

<?php
require_once './_inc/footer.php';
?>