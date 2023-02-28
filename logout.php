<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
require_once 'functions.php';

// Supprimer la clé 'user' de la session
unset($_SESSION['user']);

// Redirection vers la page d'accueil
header('Location: index.php');
exit();
?>
