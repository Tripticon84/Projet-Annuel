<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, false, true);

// Vérification de l'ID de la société
if (!isset($_GET['societe_id']) || empty($_GET['societe_id'])) {
    returnError(400, 'societe_id not provided');
    return;
}

$societyId = intval($_GET['societe_id']);
$estimate = getEstimateBySocietyId($societyId);

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
    "montant_ht" => $estimate['montant_ht'],
    "montant_tva" => $estimate['montant_tva'],
    "is_contract" => $estimate['is_contract'],
    "fichier" => $estimate['fichier'],
    "id_societe" => $estimate['id_societe']
];

echo json_encode($result);
http_response_code(200);
