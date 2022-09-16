<?php
// On initialise une session
session_start();

// On vérifie si l'utilisateur est connecté, si non, on le redirige vers la page login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
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

        .reset-link {
            margin-right: 40px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h1 class="my-5">Bonjour, <b><?php echo ucfirst(htmlspecialchars($_SESSION["utilisateur"])); ?></b>, bienvenue sur notre site.</h1>
        <p>
            <a href="reset_mdp.php" class="reset-link">REINITIALISER LE MOT DE PASSE</a>
            <a href="logout.php" class="logout-link">SE DECONNECTER</a>
        </p>
    </div>
</body>

</html>