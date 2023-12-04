<?php
    require_once 'conn1.php';
    require_once 'constructor.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Santa - Gestion des Noms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="functions.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2>Ajouter une Famille :</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="new_family" placeholder="Entrez une famille" required>
                <input type="submit" value="Ajouter">
            </form>
        </div>
        <div class="col">
            <h2>Ajouter un Nom :</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="new_name" placeholder="Entrez un nom" required>
                <input type="text" name="new_email" placeholder="Entrez un email valide" required>
                
                <!-- Ajout de la dropdownlist pour sélectionner une famille -->
                <select name="selected_family" required>
                    <option value="" disabled selected>Choisissez une famille</option>
                    
                    
                    <?php
                        // Récupération de toutes les familles de la table `famille`
                        $sql_select_families = "SELECT id_famille, nom_famille FROM famille";
                        $result_families = $conn->query($sql_select_families);

                        while ($row_family = $result_families->fetch_assoc()) {
                            echo "<option value='" . $row_family["id_famille"] . "'>" . $row_family["nom_famille"] . "</option>";
                        }
                    ?>


                </select>

                <?php
                // Vérifier si des familles existent avant d'afficher le bouton "Ajouter un Nom"
                $sql_check_families = "SELECT COUNT(*) as count FROM famille";
                $result_check_families = $conn->query($sql_check_families);
                $row_check_families = $result_check_families->fetch_assoc();
                $count_families = $row_check_families['count'];

                if ($count_families > 0) {
                    echo '<input type="submit" value="Ajouter">';
                } else {
                    echo '<input type="submit" value="Ajouter" disabled>';
                }
                ?>

            </form>
        </div>
    </div>
    <div class="row">


    <?php

        // Récupération de toutes les familles de la table `famille`
        $sql_select_families = "SELECT id_famille, nom_famille FROM famille";
        $result_families = $conn->query($sql_select_families);

        while ($row_family = $result_families->fetch_assoc()) {
            echo "<div class=\"col-md-4\">";
            echo "<h2>" . $row_family["nom_famille"] . " <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline'><input type='hidden' name='delete_family' value='" . $row_family["nom_famille"] . "'><input type='submit' class='btn delete-button' value='X'></form></h2>";

            // Récupération des noms associés à la famille
            $sql_select_names = "SELECT nom, email FROM personne WHERE id_famille = '" . $row_family["id_famille"] . "'";
            $result_names = $conn->query($sql_select_names);

            if ($result_names->num_rows > 0) {
                echo "<ul>";
                while ($row_name = $result_names->fetch_assoc()) {
                    echo ("
                        <li>


                                    " . $row_name["nom"] . " (" . $row_name["email"] .")

                                    <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' style='display:inline'> 
                                        <input type='hidden' name='delete_name' value='" . $row_name["nom"] . "'>  
                                        <input type='submit' class='btn delete-button' value='X'> 
                                    </form> 


                        </li>
                    ");
                }
                echo "</ul>";
            } else {
                echo "Aucun nom pour le moment.";
            }
            echo "</div>";
        }

    ?>


    </div>
</div>

<div class="container text-center mt-3">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirmSendMail();">
        <button type="submit" name="send_mail" class="send-mail-button">Envoyer les mails !</button>
    </form>
</div>

<?php

// Ton code RandomizeNames ici...

// Afficher les relations Secret Santa dans une liste HTML
try {
    // Sélectionner les relations Secret Santa
    $sql_select_relations = "SELECT id_giver, id_receiver FROM relation_secret_santa";
    $result_relations = $conn->query($sql_select_relations);

    if ($result_relations === false) {
        throw new Exception("Erreur lors de la récupération des relations Secret Santa : " . $conn->error);
    }

    // Créer une liste HTML
    echo '<ul>';

    while ($row_relation = $result_relations->fetch_assoc()) {
        $giver = $row_relation["id_giver"];
        $receiver = $row_relation["id_receiver"];

        // Afficher chaque relation dans un élément de liste
        echo '<li>' . $giver . ' offre un cadeau à ' . $receiver . '</li>';
    }

    echo '</ul>';
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

?>

<?php

    $conn->close();

?>

</body>
</html>
