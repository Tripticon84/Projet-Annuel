<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}



$email = '';
$limit = null;
$offset = null;

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        returnError(400, 'Invalid email format');
    }
}
if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
    if ($limit < 1) {
        returnError(400, 'Limit must be a positive and non zero number');
    }
}
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    if ($offset < 0) {
        returnError(400, 'Offset must be a positive number');
    }
}

$providers = getAllProvider($username, $limit, $offset);

$result = []; // Initialize the result array

foreach ($providers as $provider) {
    $result[] = [
        "id" => $provider['prestataire_id'],
        "email" => $provider['email'],
        "name" => $provider['nom'],
        "surname" => $provider['prenom'],
        "start_date" => $provider['date_debut_disponibilite'],
        "end_date" => $provider['date_fin_disponibilite'],
        "type" => $provider['type'],
        "description" => $provider['description'],
        "price"=> $provider['tarif']
    ];
}

echo json_encode($result);
