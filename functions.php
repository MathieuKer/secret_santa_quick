<?php

function RandomizeNames($conn){
    $sql_truncate = "TRUNCATE TABLE relation_secret_santa";
    $conn->query($sql_truncate);

    // Récupération de tous les noms de la table `personne`
    $sql_select = "SELECT nom FROM personne";
    $result = $conn->query($sql_select);

    $names = [];
    while ($row = $result->fetch_assoc()) {
        $names[] = $row["nom"];
    }

    shuffle($names);

    // Création des nouvelles relations Secret Santa
    $numNames = count($names);
    for ($i = 0; $i < $numNames; $i++) {
        $id_giver = $names[$i];
        $id_receiver = $names[($i + 1) % $numNames]; // Assurez-vous qu'une personne ne s'offre pas un cadeau à elle-même
        $sql_insert = "INSERT INTO relation_secret_santa (id_giver, id_receiver) VALUES ('$id_giver', '$id_receiver')";
        $conn->query($sql_insert);
    }
}

?>