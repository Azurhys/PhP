<?php
session_start();
require_once './_inc/functions.php';
require_once './_inc/header.php';
require_once './_inc/nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $errors = validateLoginForm($email, $password);

  if (empty($errors)) {
    if (verify_admin_credentials($email, $password)) {
      session_start();
      $_SESSION['is_authenticated'] = true;
      header('Location: admin.php');
      exit();
    } else {
      $errors[] = "L'adresse e-mail ou le mot de passe est incorrect.";
    }
  }

  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo "<div class='error'>$error</div>";
    }
  }
}
?>

<div class="login-form">
  <h2>Connexion</h2>
  <form action="login.php" method="post">
    <label for="email">Adresse e-mail:</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" id="password" required>
    <button type="submit">Se connecter</button>
  </form>
</div>

<?php
require_once './_inc/footer.php';
?>
