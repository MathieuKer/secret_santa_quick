<?php

function RandomizeNames_old($conn){
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


function RandomizeNames($conn){
    try {
        // Suppression des anciennes relations Secret Santa
        $sql_truncate = "TRUNCATE TABLE relation_secret_santa";
        $conn->query($sql_truncate);

        // Récupération de tous les noms et familles de la table `personne`
        $sql_select = "SELECT nom, id_famille FROM personne";
        $result = $conn->query($sql_select);

        if ($result === false) {
            throw new Exception("Erreur lors de la récupération des noms et familles : " . $conn->error);
        }

        $namesByFamily = [];
        while ($row = $result->fetch_assoc()) {
            $family = $row["id_famille"];
            $name = $row["nom"];
            $namesByFamily[$family][] = $name;
        }

            // Affichage de tous les noms par famille
        foreach ($namesByFamily as $family => $familyNames) {
            echo "Famille '$family':<br>";
            foreach ($familyNames as $name) {
                echo "- $name<br>";
            }
            echo "<br>";
        }

        echo "Les relations Secret Santa ont été générées avec succès.";
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
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
        } else {
            echo "Erreur lors de la suppression des utilisateurs : " . $conn->error;
            return false; // Indique une erreur
        }

        // Supprimer la famille
        $sql_supprimer_famille = "DELETE FROM famille WHERE id_famille = '$idFamille'";
        if ($conn->query($sql_supprimer_famille) === TRUE) {
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

// Fonction pour supprimer un nom
function supprimerNom($conn, $nom) {
    $sql_delete = "DELETE FROM personne WHERE nom = '$nom'";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Nom '$nom' supprimé avec succès.";
        return true;
    } else {
        echo "Erreur lors de la suppression du nom : " . $conn->error;
        return false;
    }
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