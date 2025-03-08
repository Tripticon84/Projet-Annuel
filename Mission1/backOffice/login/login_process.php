<?php

// session_start();
// if ($_SESSION) {
//     header("location: ../home.php");
//     exit();
// }

function error($message) {
    header("location: ../index.php" . "?message=" . $message);
    exit();
}


include_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


// verifier si la methode est post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error("Erreur la méthode n'est pas POST");
}

// Valider les paramètres
$required = ['username', 'password'];
if (!validateMandatoryParams($_POST, $required)) {
    error("Les paramètres ne sont pas les bons.");
}

// Connexion a la base de données
$db = getDatabaseConnection();

$username = $_POST['username'];
$salt = 'quoicoube';
$password_salt = $_POST['password'] . $salt;
$password_hash = hash("sha256", $password_salt);


$q = "SELECT admin_id, username, password FROM admin WHERE username = :username AND password = :password";
$req = $db->prepare($q);
$req->execute([
    'username' => $username,
    'password' => $password_hash
]);

// Modification ici : utilisation de fetch() au lieu de fetchAll()
$result = $req->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    error("Identifiants inconnus");
}

// Si on arrive ici, c'est que tout est OK
// Connexion

session_start();

$_SESSION["admin_id"] = $result["admin_id"];
$_SESSION["username"] = $result["username"];

// Redirection vers la page d'accueil
header("location:../home.php");
exit();
