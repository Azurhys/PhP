<?php

function processContactForm() {
    // Vérifier si le formulaire a été soumis
    if(isset($_POST['submit'])) {
        
        // Récupérer les données soumises par le formulaire
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        
        // Valider les données
        if(empty($firstname) || empty($lastname) || empty($email) || empty($subject) || empty($message)) {
            echo "Tous les champs sont obligatoires.";
            return false;
        }
        
        if(!isEmail($email)) {
            echo "L'adresse email n'est pas valide.";
            return false;
        }
        
        if(!isLong($subject) || !isLong($message)) {
            echo "Le sujet et le message doivent comporter au moins 10 caractères.";
            return false;
        }
        
        
        return true;
    }
    
    return false;
}

function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isLong($value) {
    return strlen($value) >= 10;
}

function connect_db() {
    $host = 'localhost:3306'; // remplacer par votre hôte de base de données
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

?>
