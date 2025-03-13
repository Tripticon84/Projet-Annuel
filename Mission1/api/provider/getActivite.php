<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

$limit = null;
$offset = null;
$data = getBody();


if (!isset($data['prestataire_id'])) {
    returnError(400, 'Missing id');
    return;
}


$provider = getProviderById($data['prestataire_id']);


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

$activities = getAllActivities($limit, $offset, $provider);

$result = []; // Initialize the result array

foreach ($activities as $activitie) {
    $result[] = [
        "activite_id" => $activities['evenement_id'],
        "name" => $activitie['nom'],
        "date" => $activitie['date'],
        "place" => $activitie['lieu'],
        "type" => $activitie['type'],
        "id_estimate"=>$activitie['id_devis']
    ];
}

echo json_encode($result);