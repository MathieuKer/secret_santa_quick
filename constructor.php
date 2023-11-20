<?php

require_once 'functions.php';


// Ajout d'un nom dans une famille existante si le bouton "Ajouter" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_name"]) && isset($_POST["selected_family"])) {   
    associerNomAFamille($conn, $_POST["new_name"], $_POST["selected_family"]);
    RandomizeNames($conn);
}

// Ajout d'une famille si le bouton "Ajouter" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_family"])) {
    ajouterNouvelleFamille($conn, $_POST["new_family"]);
    RandomizeNames($conn);
}

// Suppression d'un nom si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_name"])) { 
    supprimerNom($conn, $delete_name);
    RandomizeNames($conn);
}

// Suppression d'une famille si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_family"])) {
    supprimerFamilleEtUtilisateursParNom($_POST["delete_family"], $conn);
    RandomizeNames($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate"])) {
    RandomizeNames($conn);
}

?>