<?php

// On initialise une session
session_start();

// On vérifie si l'utilisateur est déjà connecté, si oui, on le redigie vers la page d'accueil.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location:bienvenue.php");
    exit();
}

require_once "config.php";

$utilisateur = $motdepasse = "";
$utilisateur_err = $motdepasse_err = $login_err = "";

// On vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On vérifie si utilisateur est vide
    if (empty(trim($_POST["utilisateur"]))) {
        $utilisateur_err = "Veuillez rentrer votre pseudo.";
    } else {
        $utilisateur = trim($_POST["utilisateur"]);
    }

    // On vérifie le mot de passe est vide
    if (empty(trim($_POST["motdepasse"]))) {
        $motdepasse_err = "Veuillez rentrer votre mot de passe.";
    } else {
        $motdepasse = trim($_POST["motdepasse"]);
    }

    // Validation des credentials
    if (empty($utilisateur_err && empty($motdepasse_err))) {
        // Préparation de la requête de sélection
        $sql = "SELECT id, utilisateur, motdepasse FROM utilisateurs WHERE utilisateur = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Liaison des variables de la requête preparée au paramètre.
            mysqli_stmt_bind_param($stmt, "s", $param_utilisateur);
            $param_utilisateur = $utilisateur;

            // Exécution de la requête
            if (mysqli_stmt_execute($stmt)) {
                // Stockage des résultats
                mysqli_stmt_store_result($stmt);

                // On vérifie si l'utilisateur selectionné existe
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // On recupère les données renvoyés par la requête
                    mysqli_stmt_bind_result($stmt, $id, $utilisateur, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        // On vérifie si le mot de passe saisi et celle recupère dans la base correspondent
                        if (password_verify($motdepasse, $hashed_password)) {
                            // Le mot de passe est correct, on commence une nouvelle session.
                            session_start();

                            // On stocker les données dans la variable session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["utilisateur"] = $utilisateur;

                            // Redirection vers la page bienvenue.php
                            header("location: bienvenue.php");
                        } else {
                            // Les mots de passe ne correspondent pas, on affiche un message.
                            $login_err = "Le pseudo ou le mot de passe est incorrect. <br/>";
                        }
                    }
                } else {
                    // L'utilisateur n'existe pas, on affiche un message générale
                    $login_err = "Le pseudo ou le mot de passe est incorrect. <br/>";
                }
            } else {
                echo "Oops! Quelque chose ne va pas. Merci de réessayer.";
            }
            // Fermeture du statement
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
    <title>Connexion</title>
    <style>
        .main-container {
            width: 40%;
            margin: auto;
            background-color: whitesmoke;
            display: flex;
            flex-direction: column;
            padding: 40px;
            margin-top: 100px;
        }

        td {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="entete">
            <h3>SE CONNECTER</h3>
            <p>Veuillez remplir ce formulaire pour vous connecter.</p>
        </div>
        <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table>
                    <tr>
                        <td><label>Pseudonyme</label></td>
                        <td><input type="text" name="utilisateur" value="<?php echo ""; ?>"></td>
                        <td><span style='color:red;'><?php echo ""; ?></span></td>
                    </tr>

                    <tr>
                        <td><label>Mot de passe</label></td>
                        <td><input type="password" name="motdepasse"></td>
                        <td><span style='color:red;'><?php echo ""; ?></span></td>
                    </tr>

                    <tr>
                        <td><input class="submit-btn" type="submit" name="submit_btn" value="SE CONNECTER"></td>
                    </tr>
                </table>
                <p>Pas encore membre? <a href="registration.php">Créée ton compte</a>.</p>
            </form>
        </div>
    </div>

</body>

</html>