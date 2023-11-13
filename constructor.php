<?php

require_once 'functions.php';


// Traitement pour associer un nouveau nom à une famille
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_name"]) && isset($_POST["selected_family"])) {
    $new_name = $_POST["new_name"];
    $selected_family = $_POST["selected_family"];

    // Vérifier si le nom n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM personne WHERE nom = '$new_name'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    $count = $row_check['count'];

    if ($count == 0) {
        // Insertion du nouveau nom avec l'id de la famille dans la table `personne`
        $sql = "INSERT INTO personne (nom, id_famille) VALUES ('$new_name', '$selected_family')";
        $conn->query($sql);
    }

    // Appeler la fonction de mélange des noms
    RandomizeNames($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_family"])) {
    // Récupération de la famille entrée dans le formulaire
    $new_family = $_POST["new_family"];

    // Vérifier si le nom n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM famille WHERE nom_famille = '$new_family'";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count == 0) {
        // Insertion de la nouvelle famille dans la table `famille`
        $sql = "INSERT INTO famille (nom_famille) VALUES ('$new_family')";
        $conn->query($sql);
    }

    RandomizeNames($conn);
}

// Suppression d'un nom si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_name"])) {
    $delete_name = $_POST["delete_name"];
    $sql_delete = "DELETE FROM personne WHERE nom = '$delete_name'";
    $conn->query($sql_delete);

    RandomizeNames($conn);
}

// Suppression d'une famille si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_family"])) {
    $delete_family = $_POST["delete_family"];
    $sql_delete = "DELETE FROM famille WHERE nom_famille = '$delete_family'";
    $conn->query($sql_delete);

    RandomizeNames($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate"])) {
    // Suppression des données de la table `relation_secret_santa`
    RandomizeNames($conn);
}


?>