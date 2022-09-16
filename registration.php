<?php
require_once "config.php";

// Définition des variables
$utilisateur = $motdepasse = $confirme_motdepasse = "";
$utilisateur_err = $motdepasse_err = $confirme_motdepasse_err = "";

// Traitement des données si le formulaire est soumis.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["utilisateur"]))) {
        $utilisateur_err = "Please enter an pseudo.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["utilisateur"]))) {
        $utilisateur_err = "Seulement des lettres, nombres, et underscore.";
    } else {
        // Préparation du requête d'insertion
        $sql = "SELECT id FROM utilisateurs WHERE utilisateur = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Liaison du variable de la requête preparée au paramètre.
            mysqli_stmt_bind_param($stmt, "s", $param_utilisateur);

            $param_utilisateur = trim($_POST["utilisateur"]);

            // Exécution de la requête
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                // Vérification si la ligne existe déjà dans la base de données.
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $utilisateur_errs = "Pseudo déjà utilisé.";
                } else {
                    $utilisateur = trim($_POST["utilisateur"]);
                }
            } else {
                echo "Oops! Votre requête n'a pas été exécuté.";
            }
            // Fermeture du statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validation du mot de passe
    if (empty(trim($_POST["motdepasse"]))) {
        $motdepasse_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["motdepasse"])) < 6) {
        $motdepasse_err = "Le mot de passe doit avoir au minimum 6 caractères.";
    } else {
        $motdepasse = trim($_POST["motdepasse"]);
    }

    // Validation de la confirmation du mot de passe
    if (empty(trim($_POST["confirme_motdepasse"]))) {
        $confirme_motdepasse_err = "Veuillez confirmer votre mot de passe.";
    } else {
        $confirme_motdepasse = trim($_POST["confirme_motdepasse"]);
        if (empty($motdepasse_err) && ($motdepasse != $confirme_motdepasse)) {
            $confirme_motdepasse_err = "Les mots de passe ne correspondent pas.";
        }
    }

    // Vérification des entrées avant insertion dans la base de données.
    if (empty($utilisateur_err) && empty($motdepasse_err) && empty($confirme_motdepasse_err)) {
        // Préparation du requête d'insertion
        $query = "INSERT INTO utilisateurs (utilisateur, motdepasse) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $query)) {
            // Liaison des variables de la requête preparée aux paramètres.
            mysqli_stmt_bind_param($stmt, "ss", $param_utilisateur, $param_motdepasse);
            echo "dfd = " . $utilisateur . "</br>";
            echo "dfd = " . $motdepasse . "</br>";

            $param_utilisateur = $utilisateur;
            $param_motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT); // Creates a password hash

            // Exécution de la requête
            if (mysqli_stmt_execute($stmt)) {
                // Rediraction à la page login.php
                header("location: login.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Fermeture du statement
            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        .main-container {
            width: 40%;
            margin: auto;
            background-color: whitesmoke;
            display: flex;
            flex-direction: column;
            padding: 50px;
            margin-top: 100px;
        }

        .entete {
            display: flex;
            flex-direction: column;
            align-items: start;
        }

        td {
            padding: 5px;
        }

        .submit-btn {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="entete">
            <h3>CREATION DE COMPTE</h3>
            <p>Veuillez remplir ce formulaire pour créer votre compte.</p>
        </div>
        <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table>
                    <tr>
                        <td><label>Pseudonyme</label></td>
                        <td><input type="text" name="utilisateur" value="<?php echo $utilisateur; ?>"></td>
                        <td><span style='color:red;'><?php echo $utilisateur_err; ?></span></td>
                    </tr>

                    <tr>
                        <td><label>Mot de passe</label></td>
                        <td><input type="password" name="motdepasse"></td>
                        <td><span style='color:red;'><?php echo $motdepasse_err; ?></span></td>
                    </tr>
                    <tr>
                        <td><label>Confirmer mot de passe</label></td>
                        <td><input type="password" name="confirme_motdepasse"></td>
                        <td><span style='color:red;'><?php echo "$confirme_motdepasse_err"; ?></span></td>
                    </tr>

                    <tr>
                        <td>
                            <input class="submit-btn" type="submit" name="submit_btn" value="S'INSCRIRE">
                            <input class="reset-btn" type="reset" name="reset_btn " value="RESET">
                        </td>
                    </tr>

                </table>
                <p>Déjà membre? <a href="login.php">Connecter-vous ici</a>.</p>
            </form>
        </div>
    </div>

</body>

</html>