<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

// // Vérification du token d'authentification
// if (!isset($_GET['token'])) {
//     returnError(401, 'Token not provided');
//     return;
// }
// tokenVerification($_GET['token']);

// Vérification de l'ID de la societe
if (!isset($_GET['devis_id']) || empty($_GET['devis_id'])) {
    returnError(400, 'devis id  not provided');
    return;
}

$estimateId = intval($_GET['devis_id']);
$estimate = getEstimateById($estimateId);

if (!$estimate) {
    returnError(404, 'Estimate or Contract not found');
    return;
}

$result = [
    "devis_id" => $estimate['devis_id'],
    "date_debut" => $estimate['date_debut'],
    "date_fin" => $estimate['date_fin'],
    "statut" => $estimate['statut'],
    "montant" => $estimate['montant'],
    "is_contract" => $estimate['is_contract'],
    "fichier" => $estimate['fichier'],
    "id_societe" => $estimate['id_societe']
];


echo json_encode($result);
http_response_code(200);
