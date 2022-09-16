<?php
// Initialisation de la session
session_start();

// On vérifie si l'utilisateur est déjà connecté, sinon redirection vers la page login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

// On définie les variables et on les initialise avec valeurs vides
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// On procède au traitement seulement si le formulaire est soumis.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validation du nouveau mot de passe
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have atleast 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validation de la confirmation du nouveau mot de passe
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validation des données d'entrées avant la mise à jour dans la base
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Préparation de la requête de mise à jour
        $sql = "UPDATE utilisateurs SET motdepasse = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Laisons des variables de la requête préparés aux paramètres
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Mise à jour des variables
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Exécution de la requête préparée
            if (mysqli_stmt_execute($stmt)) {
                // Mot de passe mise à jour avec succès. Destruction de la session et redirection à la page de login
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Fermeture de la requête
            mysqli_stmt_close($stmt);
        }
    }

    // Fermeture de la connexion
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinitialisation mot de passe</title>
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }

        .main-container {
            width: 40%;
            margin: auto;
            background-color: whitesmoke;
            display: flex;
            flex-direction: column;
            padding: 50px;
            margin-top: 100px;
        }

        div {
            padding: 5px;
        }

        .btn-soumettre {
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h2>REINITIALISATION DU MOT DE PASSE</h2>
        <p>Veuillez remplir ce formulaire pour changer le mot de passe.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" value="">
                <span class="invalid-feedback"><?php echo ""; ?></span>
            </div>
            <div>
                <label>Confirmer mot de passe</label>
                <input type="password" name="confirm_password" value="">
                <span class=" invalid-feedback"><?php echo "" ?></span>
            </div>
            <div>
                <input type="submit" class="btn-soumettre" value="SOUMETTRE">
                <a href="bienvenue.php">Annuler</a>
            </div>
        </form>
    </div>
</body>

</html>