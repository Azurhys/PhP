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

?>
