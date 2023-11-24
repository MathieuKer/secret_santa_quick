<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/Users/mkeromnes/Sites/localhost/secret_santa_quick/vendor/phpmailer/phpmailer/src/Exception.php';
require '/Users/mkeromnes/Sites/localhost/secret_santa_quick/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/Users/mkeromnes/Sites/localhost/secret_santa_quick/vendor/phpmailer/phpmailer/src/SMTP.php';

// Fonction pour mélanger un tableau
function shuffleArraysInsideArray(&$a) {
    $n = count($a);
    for ($i = $n - 1; $i > 0; $i--) {
        $j = rand(0, $i);
        list($a[$i], $a[$j]) = array($a[$j], $a[$i]);
    }
}

function shuffleArrayOfArrays(&$arrayOfArrays)
{
    // Shuffle the outer array
    shuffle($arrayOfArrays);

    // Shuffle each inner array
    foreach ($arrayOfArrays as &$innerArray) {
        shuffle($innerArray);
    }

    return $arrayOfArrays;
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
        
        foreach ($namesByFamily as &$family_group) {
            shuffleArraysInsideArray($family_group);
        }

        // Mélanger les groupes eux-mêmes
        shuffleArrayOfArrays($namesByFamily);

        // Créer une liste plate à partir des groupes mélangés
        $list = array_merge(...$namesByFamily);

        // Associer les éléments de la liste
        $pairs = [];

        for ($i = 0; $i < floor(count($list) / 2); $i++) {
            $pairs[] = [$list[$i], $list[$i + floor(count($list) / 2)]];
        }

        if (count($list) % 2) {
            $pairs[] = [array_pop($list)];
        }

        // Afficher le résultat des associations
        $result = array_merge(...$pairs);

        foreach ($result as $index => $element) {
            $nextIndex = ($index + 1) % count($result);
            $sql_insert = "INSERT INTO relation_secret_santa (id_giver, id_receiver) VALUES ('$element', '$result[$nextIndex]')";
            $conn->query($sql_insert);
        }
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
        return false; // Indique une erreur
    }
}

// Fonction pour supprimer un nom
function supprimerNom($conn, $nom) {
    $sql_delete = "DELETE FROM personne WHERE nom = '$nom'";
    if ($conn->query($sql_delete) === TRUE) {
        return true;
    } else {
        echo "Erreur lors de la suppression du nom : " . $conn->error;
        return false;
    }
}

// Fonction pour associer un nouveau nom à une famille
function associerNomMailAFamille($conn, $new_name, $new_email, $selected_family) {
    // Vérifier si le nom n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM personne WHERE nom = '$new_name' and email = '$new_email'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    $count = $row_check['count'];

    if ($count == 0) {
        // Insertion du nouveau nom avec l'id de la famille dans la table `personne`
        $sql = "INSERT INTO personne (nom, email, id_famille) VALUES ('$new_name', '$new_email', '$selected_family')";
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

function sendMails($conn){

    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.smtp2go.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'secret-santa.com';
        $mail->Password = 'Not24get';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPDebug = 2;
    
        // Destinataire, sujet, corps du message, etc.
        $mail->setFrom('mathieu.kero@gmail.com', 'Mathieu');
        $mail->addAddress('mkeromnes@techso.com', 'Mathieu');
        $mail->Subject = 'Sujet de l\'e-mail';
        $mail->Body = 'Contenu de l\'e-mail';
    
        // Envoi de l'e-mail
        $mail->send();
        echo 'L\'e-mail a été envoyé avec succès.';

        header("Location: end_of_game.html");
        exit;
    } catch (Exception $e) {
        echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
    }
}

?>