<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  include('../../_inc/header.php');
  include('../_inc/nav.php');
  require_once '../../_inc/functions.php';
  checkAuthentication();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appeler la fonction processLoginForm pour traiter le formulaire
    $success = processGameForm($_POST['title'], $_POST['description'], $_POST['release_date'], $_POST['poster'], $_POST['price']);
    // Si le formulaire a été validé avec succès, rediriger vers la page d'accueil
    if ($success) {
      header('Location: admin/games/index.php');
      exit;
    } 
  }
?>
<div class="container">
<form action="form.php" method="POST">
  <input type="hidden" name="id" value="">
  <div class="form-group mt-3">
    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" class="form-control">
  </div>
  <div class="form-group mt-3">
    <label for="description">Description :</label>
    <textarea name="description" id="description" class="form-control"></textarea>
    </div>
  <div class="form-group mt-3">
    <label for="release_date">Date de sortie :</label>
    <input type="date" name="release_date" id="release_date" class="form-control">
    </div>
  <div class="form-group mt-3">
    <label for="poster">URL de l'affiche :</label>
    <input type="text" name="poster" id="poster" class="form-control">
    </div>
  <div class="form-group mt-3">
    <label for="price">Prix :</label>
    <input type="text" name="price" id="price" class="form-control">
  </div>
  <div class="d-flex justify-content-center">
    <button type="submit" class="btn btn-success mt-3 ">Ajouter</button>
  </div>
</form>
</div>

<?php
include('../../_inc/footer.php');
?>
