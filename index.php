<?php
    require_once 'conn1.php';
    require_once 'constructor.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Secret Santa - Gestion des Noms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <link rel="stylesheet" type="text/css" href="chemin/vers/style.css">
</head>
<body>

<h2>Ajouter une Famille :</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="new_family" placeholder="Entrez une famille" required>
    <input type="submit" value="Ajouter">
</form>

<h2>Ajouter un Nom :</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="new_name" placeholder="Entrez un nom" required>
    
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="submit" name="generate" value="Générer">
</form>

<?php


// Récupération de toutes les familles de la table `famille`
$sql_select_families = "SELECT id_famille, nom_famille FROM famille";
$result_families = $conn->query($sql_select_families);

?>
<div class="container">
  <div class="row">
<?php

while ($row_family = $result_families->fetch_assoc()) {
    echo "<div class=\"col\">";
    echo "<h2>" . $row_family["nom_famille"] . " <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline'><input type='hidden' name='delete_family' value='" . $row_family["nom_famille"] . "'><input type='submit' value='X'></form></h2>";

    // Récupération des noms associés à la famille
    $sql_select_names = "SELECT nom FROM personne WHERE id_famille = '" . $row_family["id_famille"] . "'";
    $result_names = $conn->query($sql_select_names);

    if ($result_names->num_rows > 0) {
        echo "<ul>";
        while ($row_name = $result_names->fetch_assoc()) {
            echo "<li>" . $row_name["nom"] . "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline'><input type='hidden' name='delete_name' value='" . $row_name["nom"] . "'><input type='submit' value='X'></form></li>";
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
<?php



// Affichage du contenu de la table `relation_secret_santa`
$sql_select_relation = "SELECT id_giver, id_receiver FROM relation_secret_santa";
$result_relation = $conn->query($sql_select_relation);

if ($result_relation->num_rows > 0) {
    echo "<h2>Relations Secret Santa :</h2>";
    echo "<ul>";
    while ($row = $result_relation->fetch_assoc()) {
        echo "<li>" . $row["id_giver"] . " offre à " . $row["id_receiver"] . "</li>";
    }
    echo "</ul>";
} 

$conn->close();
?>

</body>
</html>
