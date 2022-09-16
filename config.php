<?php
// Constantes de connexion à la base
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'dwwm_vervins');

// Connexion à la base
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($link === false) {
    die("ERROR: Could not connect ." . mysqli_connect_error());
}
