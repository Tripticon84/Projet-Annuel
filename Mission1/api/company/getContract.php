<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

$idSociete = intval($_GET['societe_id']);
$is_contract = true;
$company = getSocietyById($idSociete);

if (!$company) {
    returnError(404, 'Company not found');
    return;
}

$estimates = getCompanyEstimate($idSociete, $is_contract);

if (!$estimates) {
    returnError(404, 'Estimates not found');
    return;
}

$result = []; // Initialize the result array

foreach ($estimates as $estimate) {
    $result[] = [
        "devis_id" => $estimate['devis_id'],
        "stard_date" => $estimate['date_debut'],
        "end_date" => $estimate['date_fin'],
        "statut" => $estimate['statut'],
        "montant" => $estimate['montant']
    ];
}

if (empty($result)) {
    returnError(404, 'No estimates found');
    return;
}

echo json_encode($result);