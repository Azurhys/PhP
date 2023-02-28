<?php
  session_start();
  include('./_inc/header.php');
  include('./_inc/nav.php');
  require_once './_inc/functions.php';

  $games = get_random_games(3);
?>

<!-- Contenu de la page -->
<h1>Accueil </h1>
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
  include('./_inc/footer.php');
?>
