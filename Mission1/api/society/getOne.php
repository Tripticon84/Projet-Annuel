<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/society.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

// Vérification du token d'authentification
if (!isset($_GET['token'])) {
    returnError(401, 'Token not provided');
    return;
}
tokenVerification($_GET['token']);

// Vérification de l'ID de l'employee
if (!isset($_GET['societe_id']) || empty($_GET['societe_id'])) {
    returnError(400, 'Company ID not provided');
    return;
}

$societyId = intval($_GET['societe_id']);
$society = getSociety($societyId);

if (!$society) {
    returnError(404, 'Company not found');
    return;
}

$result = [
    "societe_id" => $societe['societe_id'],
    "nom" => $societe['nom'],
    "contact_person" => $societe['contact_person'],
    "adresse" => $societe['adresse'],
    "email" => $societe['email'],
    "telephone" => $societe['telephone'],
    "date_creation" => $societe['date_creation']
];

echo json_encode($result);
http_response_code(200);
