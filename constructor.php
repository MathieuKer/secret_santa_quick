<?php

require_once 'functions.php';


// Ajout d'un nom dans une famille existante si le bouton "Ajouter" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_name"]) && !empty($_POST["new_email"]) && isset($_POST["selected_family"])) {   
    associerNomMailAFamille($conn, $_POST["new_name"], $_POST["new_email"], $_POST["selected_family"]);
}

// Ajout d'une famille si le bouton "Ajouter" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_family"])) {
    ajouterNouvelleFamille($conn, $_POST["new_family"]);
}

// Suppression d'un nom si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_name"])) { 
    supprimerNom($conn, $_POST["delete_name"]);
}

// Suppression d'une famille si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_family"])) {
    supprimerFamilleEtUtilisateursParNom($_POST["delete_family"], $conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send_mail"])) {
    RandomizeNames($conn);
    sendMails($conn);
}

?>