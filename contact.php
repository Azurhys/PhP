<?php
if (isset($_POST['submit'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $submissionDate = new DateTime();
}
session_start();
?>

<?php
include('./_inc/functions.php');
processContactForm($name, $email, $message);
?>

<?php include('./_inc/header.php'); ?>

<?php include('./_inc/nav.php'); ?>

<main>
  <h1>Contactez-nous</h1>
  <form method="post">
    <label for="firstname">Prénom :</label>
    <input type="text" id="firstname" name="firstname" required>
    <br><br>
    <label for="lastname">Nom :</label>
    <input type="text" id="lastname" name="lastname" required>
    <br><br>
    <label for="email">E-mail :</label>
    <input type="email" id="email" name="email" required>
    <br><br>
    <label for="subject">Sujet :</label>
    <input type="text" id="subject" name="subject" required>
    <br><br>
    <label for="message">Message :</label>
    <textarea id="message" name="message" required></textarea>
    <br><br>
    <button type="submit" name="submit">Envoyer</button>
  </form>

  <?php if (isset($_POST['submit'])) : ?>
    <h2>Récapitulatif :</h2>
    <p>Prénom : <?= $firstname ?></p>
    <p>Nom : <?= $lastname ?></p>
    <p>E-mail : <?= $email ?></p>
    <p>Sujet : <?= $subject ?></p>
    <p>Message : <?= $message ?></p>
    <p>Date de soumission : <?= $submissionDate->format('d/m/Y H:i:s') ?></p>
  <?php endif; ?>
</main>

<?php include('./_inc/footer.php'); ?>
