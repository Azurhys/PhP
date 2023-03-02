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
      header('Location: ../index.php');
      exit;
    }
  }
  
  function processGameForm($data)
{
    // Validation des données du formulaire
    $constraints = getGameFormConstraints();
    $errors = validateData($data, $constraints);
    var_dump($data['title'], $data['description'], $data['release_date']);
    // Si le formulaire n'est pas valide, stocker les erreurs dans la session et rediriger
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $data;
        return false;
    }

    // Récupération des données du formulaire
    $id = $data['id'];
    $title = $data['title'];
    $description = $data['description'];
    $release_date = $data['release_date'];
    $poster = $data['poster'];
    $price = $data['price'];
      
    // Vérification si c'est une création ou une modification
    if (empty($id)) {
        // Création d'un nouveau jeu vidéo
        insertGame($title, $description, $release_date, $poster, $price);
    } else {
        // Modification d'un jeu vidéo existant
        updateGame($id, $title, $description, $release_date, $poster, $price);
    }

    // Redirection vers la liste des jeux vidéo
    header('Location: index.php');
    exit;
}

/**
 * Retourne les contraintes de validation du formulaire des jeux vidéo
 *
 * @return array Les contraintes de validation
 */
function getGameFormConstraints()
{
    return [
        'id' => [], // Champ caché, pas besoin de validation
        'title' => [
            'required' => true,
            'min_length' => 3,
            'max_length' => 255,
        ],
        'description' => [
            'required' => true,
            'min_length' => 10,
        ],
        'release_date' => [
            'required' => true,
            'date_format' => 'Y-m-d',
        ],
        'poster' => [
            'required' => true,
            'url' => true,
            'max_length' => 255,
        ],
        'price' => [
            'required' => true,
            'numeric' => true,
            'isFloatInRange' => true,
        ],
    ];
}

function isFloatInRange($input, $min, $max)
{
    $options = [
        'options' => [
            'min_range' => $min,
            'max_range' => $max,
        ],
    ];

    return filter_var($input, FILTER_VALIDATE_FLOAT, $options) !== false;
}

function insertGame($data) {
  $conn = connect_db();
  $title = $conn->real_escape_string($data['title']);
  $description = $conn->real_escape_string($data['description']);
  $release_date = $conn->real_escape_string($data['release_date']);
  $poster = $conn->real_escape_string($data['poster']);
  $price = $conn->real_escape_string($data['price']);

  $sql = "INSERT INTO game (title, description, release_date, poster, price) VALUES ('$title', '$description', '$release_date', '$poster', '$price')";
  
  $query = $conn->query($sql);
  $last_id = $conn->insert_id;
  
  $conn->close();
  return $last_id;
}

function validateData($data) {
  $errors = [];
  
  // Vérification du titre
  if (empty($data['title'])) {
    $errors[] = "Le champ titre est obligatoire.";
  } else if (strlen($data['title']) > 255) {
    $errors[] = "Le champ titre ne doit pas dépasser 255 caractères.";
  }
  
  // Vérification de la description
  if (empty($data['description'])) {
    $errors[] = "Le champ description est obligatoire.";
  }
  
  // Vérification de la date de sortie
  if (empty($data['release_date'])) {
    $errors[] = "Le champ date de sortie est obligatoire.";
  } else {
    $date = DateTime::createFromFormat('Y-m-d', $data['release_date']);
    if (!$date || $date->format('Y-m-d') !== $data['release_date']) {
      $errors[] = "Le champ date de sortie doit être au format AAAA-MM-JJ.";
    }
  }
  
  // Vérification de l'image
  if (empty($data['poster'])) {
    $errors[] = "Le champ image est obligatoire.";
  }
  
  // Vérification du prix
  if (empty($data['price'])) {
    $errors[] = "Le champ prix est obligatoire.";
  } else if (!isFloatInRange($data['price'], 0, 999.99)) {
    $errors[] = "Le champ prix doit être un nombre décimal compris entre 0 et 999.99.";
  }
  
  return $errors;
}

function updateGame($data)
{
  $conn = connect_db();
  
  // Préparation de la requête SQL
  $sql = "UPDATE game SET title=?, description=?, release_date=?, poster=?, price=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  
  // Récupération des données du formulaire
  $title = $data['title'];
  $description = $data['description'];
  $release_date = $data['release_date'];
  $poster = $data['poster'];
  $price = $data['price'];
  $id = $data['id'];

  // var_dump( $title, $description, $release_date, $poster, $price, $id);exit;

  $stmt->bind_param('ssssdi', $title, $description, $release_date, $poster, $price, $id);
  
  // Exécution de la requête SQL
  $stmt->execute();
  
  $conn->close();
}
?>
