<?php

session_start();
if ($_SESSION) {
    header("location: ../home.php");
    exit();
}


include_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


// verifier si la methode est post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    returnError(405, 'Method not allowed');
}

// Valider les paramètres
$required = ['email', 'password'];
if (!validateMandatoryParams($_POST, $required)) {
    returnError(400, 'Missing required parameters');
}

// Connexion a la base de données
$db = getDatabaseConnection();

$email = $_POST['email'];
$salt = 'quoicoube';
$password_salt = $_POST['password'] . $salt;
$password_hash = hash("sha256", $password_salt);


$q = "SELECT admin_id, email, password FROM admin WHERE email = :email AND password = :password";
$req = $db->prepare($q);
$req->execute([
    'email' => $email,
    'password' => $password_hash
]);

$result = $req->fetch();

if (empty($result)) {
    header("location:../index.php" . '?' . 'message=Identifiants inconnue');
}

// Si on arrive ici, c'est que tout est OK
// Connexion

session_start();

$_SESSION["admin_id"] = $result["admin_id"];
$_SESSION["email"] = $result["email"];

// Redirection vers la page d'accueil
header("location:../home.php");
exit();
