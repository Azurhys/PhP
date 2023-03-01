<?php

function processContactForm($name, $email, $message)
{
  $errors = validateContactForm($name, $email, $message);

  if (empty($errors)) {
    // Envoyer le message de contact par e-mail ou le sauvegarder dans la base de données

    // Enregistrer un message flash dans la session pour informer l'utilisateur que le message a été envoyé avec succès
    $_SESSION['notice'] = "Vous serez contacté dans les plus brefs délais.";

    // Rediriger l'utilisateur vers la page d'accueil
    header('Location: index.php');
    exit();
  }

  return $errors;
}

function validateContactForm($name, $email, $message)
{
  $errors = [];

  // Vérifier le nom
  if (empty($name)) {
    $errors[] = "Le nom est obligatoire.";
  }

  // Vérifier l'adresse e-mail
  if (empty($email)) {
    $errors[] = "L'adresse e-mail est obligatoire.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'adresse e-mail n'est pas valide.";
  }

  // Vérifier le message
  if (empty($message)) {
    $errors[] = "Le message est obligatoire.";
  }

  return $errors;
}


function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isLong($value) {
    return strlen($value) >= 10;
}

function connect_db() {
    $host = 'localhost'; // remplacer par votre hôte de base de données
    $username = 'root'; // remplacer par votre nom d'utilisateur
    $password = ''; // remplacer par votre mot de passe
    $database = 'videogames'; // remplacer par le nom de votre base de données
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
      die("Connexion échouée: " . $conn->connect_error);
    }
    
    return $conn;
  }

  // fonction pour retourner n jeux vidéo sélectionnés aléatoirement
function get_random_games($n) {
    $conn = connect_db();
    $sql = "SELECT * FROM game ORDER BY RAND() LIMIT $n";
    $result = $conn->query($sql);
    $games = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $games[] = $row;
      }
    }
    $conn->close();
    return $games;
  }
  
  // fonction pour retourner tous les jeux vidéo présents dans la table game
  function get_all_games() {
    $conn = connect_db();
    $sql = "SELECT * FROM game";
    $result = $conn->query($sql);
    $games = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $games[] = $row;
      }
    }
    $conn->close();
    return $games;
  }
  
  // fonction pour retourner un jeu vidéo à l'aide de son identifiant
  function get_game_by_id($id) {
    $conn = connect_db();
    $sql = "SELECT * FROM game WHERE id=$id";
    $result = $conn->query($sql);
    $game = null;
    if ($result->num_rows > 0) {
      $game = $result->fetch_assoc();
    }
    $conn->close();
    return $game;
  }

  function validateLoginForm($email, $password) {
    $errors = array();
  
    if (empty($email)) {
      $errors[] = "L'adresse e-mail est obligatoire.";
    }
  
    if (empty($password)) {
      $errors[] = "Le mot de passe est obligatoire.";
    }
  
    return $errors;
  }

  function get_admin_by_email($email) {
    $conn = connect_db();
    $email = $conn->real_escape_string($email);
    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = $conn->query($sql);
    $admin = null;

    if ($result->num_rows > 0) {
      $admin = $result->fetch_assoc();
    }
    $conn->close();
    return $admin;
}

function verify_admin_credentials($email, $password) {
    $admin = get_admin_by_email($email);
    // exit(var_dump( password_verify($password, $admin['password'])  ));
    if (!$admin) {
      return false;
    }
    return password_verify($password, $admin['password']);
  }

  
  function getSessionFlashMessage($key)
  {
    if (array_key_exists($key, $_SESSION)) {
      $value = $_SESSION[$key];
      unset($_SESSION[$key]);
      return $value;
    }
    return null;
  }

  
  function processLoginForm($email, $password)
  {
    $errors = validateLoginForm($email, $password);
    if (empty($errors)) {
      // Vérifier si les identifiants de l'administrateur sont valides
      if (verify_admin_credentials($email, $password)) {
        // Stocker l'identifiant de l'administrateur dans la session
        $admin_id = get_admin_by_email($email);
        $_SESSION['user'] = $admin_id;
        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: index.php');
        exit();
      } else {
        $errors[] = "Identifiants incorrects.";
      }
    } else {
      // Enregistrer un message flash dans la session pour informer l'utilisateur des erreurs
      $_SESSION['notice'] = "Identifiants incorrects.";
    }
    
    return $errors;
  }
  function getSessionData($key)
  {
    if (array_key_exists($key, $_SESSION)) {
      return $_SESSION[$key];
    } else {
      return null;
    }
  }

  function checkAuthentication() {
    if (!array_key_exists('user', $_SESSION)) {
      $_SESSION['notice'] = 'Accès refusé';
      header('Location: index.php');
      exit;
    }
  }
  
?>
