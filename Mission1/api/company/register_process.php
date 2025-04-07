<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/siret.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /frontOffice/societe/register/register.php');
    exit();
}

// Récupérer et nettoyer les données du formulaire
$nom = trim($_POST['nom'] ?? '');
$siret = trim($_POST['siret'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact_person = trim($_POST['contact_person'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation des données
$errors = [];

if (empty($nom)) {
    $errors[] = "Le nom de l'entreprise est requis";
}

if (empty($siret) || !preg_match('/^[0-9]{14}$/', $siret)) {
    $errors[] = "Le numéro SIRET doit contenir 14 chiffres";
}

if (empty($adresse)) {
    $errors[] = "L'adresse est requise";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'email est invalide";
}

if (empty($contact_person)) {
    $errors[] = "Le nom du contact est requis";
}

if (empty($telephone) || !preg_match('/^[0-9]{10}$/', $telephone)) {
    $errors[] = "Le numéro de téléphone doit contenir 10 chiffres";
}

if (strlen($password) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
}

if ($password !== $confirm_password) {
    $errors[] = "Les mots de passe ne correspondent pas";
}

// Si des erreurs sont présentes, retourner au formulaire avec les erreurs
if (!empty($errors)) {
    $errors_json = urlencode(json_encode($errors));
    $form_data = urlencode(json_encode([
        'nom' => $nom,
        'siret' => $siret,
        'adresse' => $adresse,
        'email' => $email,
        'contact_person' => $contact_person,
        'telephone' => $telephone
    ]));
    header('Location: /frontOffice/societe/register/register.php?errors=' . $errors_json . '&form_data=' . $form_data);
    exit();
}

//Vérification du SIRET via l'API INSEE
$siretInfo = getInseeCompanyInfoBySiret($siret);

if (empty($siretInfo) || isset($siretInfo['error'])) {
    $errors = ["Le numéro SIRET n'est pas valide ou n'existe pas"];
    $errors_json = urlencode(json_encode($errors));
    $form_data = urlencode(json_encode([
        'nom' => $nom,
        'siret' => $siret,
        'adresse' => $adresse,
        'email' => $email,
        'contact_person' => $contact_person,
        'telephone' => $telephone
    ]));
    header('Location: /frontOffice/societe/register/register.php?errors=' . $errors_json . '&form_data=' . $form_data);
    exit();
}

// Les données sont valides, on les stocke en session pour l'étape suivante
$_SESSION['company_data'] = [
    'nom' => $nom,
    'siret' => $siret,
    'adresse' => $adresse,
    'email' => $email,
    'contact_person' => $contact_person,
    'telephone' => $telephone,
    'password' => $password
];

// Redirection vers la page de choix d'abonnement
header('Location: /frontOffice/societe/register/subscription.php');
exit();
?>
