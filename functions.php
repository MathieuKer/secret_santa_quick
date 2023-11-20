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

function supprimerFamilleEtUtilisateursParNom($nomFamille, $conn) {
    // Rechercher l'ID de la famille par le nom
    $sql_select_id_famille = "SELECT id_famille FROM famille WHERE nom_famille = '$nomFamille'";
    $result_id_famille = $conn->query($sql_select_id_famille);

    if ($result_id_famille->num_rows > 0) {
        $row_id_famille = $result_id_famille->fetch_assoc();
        $idFamille = $row_id_famille['id_famille'];

        // Supprimer tous les utilisateurs associés à la famille
        $sql_supprimer_utilisateurs = "DELETE FROM personne WHERE id_famille = '$idFamille'";
        if ($conn->query($sql_supprimer_utilisateurs) === TRUE) {
            echo "Utilisateurs supprimés avec succès.";
        } else {
            echo "Erreur lors de la suppression des utilisateurs : " . $conn->error;
            return false; // Indique une erreur
        }

        // Supprimer la famille
        $sql_supprimer_famille = "DELETE FROM famille WHERE id_famille = '$idFamille'";
        if ($conn->query($sql_supprimer_famille) === TRUE) {
            echo "Famille supprimée avec succès.";
            return true; // Indique le succès
        } else {
            echo "Erreur lors de la suppression de la famille : " . $conn->error;
            return false; // Indique une erreur
        }
    } else {
        echo "La famille avec le nom '$nomFamille' n'a pas été trouvée.";
        return false; // Indique une erreur
    }
}

// Suppression d'un nom si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_name"])) {
    $delete_name = $_POST["delete_name"];
    $sql_delete = "DELETE FROM personne WHERE nom = '$delete_name'";
    $conn->query($sql_delete);

    RandomizeNames($conn);
}

// Fonction pour associer un nouveau nom à une famille
function associerNomAFamille($conn, $new_name, $selected_family) {
    // Vérifier si le nom n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM personne WHERE nom = '$new_name'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    $count = $row_check['count'];

    if ($count == 0) {
        // Insertion du nouveau nom avec l'id de la famille dans la table `personne`
        $sql = "INSERT INTO personne (nom, id_famille) VALUES ('$new_name', '$selected_family')";
        if ($conn->query($sql) === TRUE) {
            echo "Nom '$new_name' associé à la famille avec succès.";
            return true;
        } else {
            echo "Erreur lors de l'association du nom à la famille : " . $conn->error;
            return false;
        }
    } else {
        echo "Le nom '$new_name' existe déjà.";
        return false;
    }
}

// Fonction pour ajouter une nouvelle famille
function ajouterNouvelleFamille($conn, $new_family) {
    // Vérifier si le nom de famille n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM famille WHERE nom_famille = '$new_family'";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count == 0) {
        // Insertion de la nouvelle famille dans la table `famille`
        $sql = "INSERT INTO famille (nom_famille) VALUES ('$new_family')";
        if ($conn->query($sql) === TRUE) {
            echo "Famille '$new_family' ajoutée avec succès.";
            return true;
        } else {
            echo "Erreur lors de l'ajout de la famille : " . $conn->error;
            return false;
        }
    } else {
        echo "La famille '$new_family' existe déjà.";
        return false;
    }
}

?>