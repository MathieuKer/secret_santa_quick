<!DOCTYPE html>
<html>
<head>
    <title>Secret Santa - Gestion des Noms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
    </style>
</head>
<body>

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secret_santa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_name"])) {
    // Récupération du nom entré dans le formulaire
    $new_name = $_POST["new_name"];

    // Vérifier si le nom n'existe pas déjà
    $sql_check = "SELECT COUNT(*) as count FROM personne WHERE nom = '$new_name'";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count == 0) {
        // Insertion du nouveau nom dans la table `personne`
        $sql = "INSERT INTO personne (nom) VALUES ('$new_name')";
        $conn->query($sql);
    }
}

// Suppression d'un nom si le bouton "Supprimer" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_name"])) {
    $delete_name = $_POST["delete_name"];
    $sql_delete = "DELETE FROM personne WHERE nom = '$delete_name'";
    $conn->query($sql_delete);
}

// Récupération de tous les noms de la table `personne`
$sql_select = "SELECT nom FROM personne";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    echo "<h2>Liste des Noms :</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row["nom"] . " <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline'><input type='hidden' name='delete_name' value='" . $row["nom"] . "'><input type='submit' value='Supprimer'></form></li>";
    }
    echo "</ul>";
} else {
    echo "Aucun nom pour le moment.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate"])) {
    // Suppression des données de la table `relation_secret_santa`
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
        $giver = $names[$i];
        $receiver = $names[($i + 1) % $numNames]; // Assurez-vous qu'une personne ne s'offre pas un cadeau à elle-même
        $sql_insert = "INSERT INTO relation_secret_santa (giver, receiver) VALUES ('$giver', '$receiver')";
        $conn->query($sql_insert);
    }
}

// Affichage du contenu de la table `relation_secret_santa`
$sql_select_relation = "SELECT giver, receiver FROM relation_secret_santa";
$result_relation = $conn->query($sql_select_relation);

if ($result_relation->num_rows > 0) {
    echo "<h2>Relations Secret Santa :</h2>";
    echo "<ul>";
    while ($row = $result_relation->fetch_assoc()) {
        echo "<li>" . $row["giver"] . " offre à " . $row["receiver"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Aucune relation pour le moment.";
}

$conn->close();
?>

<h2>Ajouter un Nom :</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="new_name" placeholder="Entrez un nom" required>
    <input type="submit" value="Ajouter">
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="submit" name="generate" value="Générer">
</form>

</body>
</html>
