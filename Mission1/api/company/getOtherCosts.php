<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, false, false);


$idSociete = intval($_GET['societe_id']);
$company = getSocietyById($idSociete);

if (!$company) {
    returnError(404, 'Company not found');
    return;
}

$otherCosts = getCompanyOtherCost($idSociete);

if (!$otherCosts) {
    returnError(404, 'otherCost not found');
    return;
}

$result = []; // Initialize the result array

foreach ($otherCosts as $otherCost) {
    $result[] = [
        "other_cost_id" => $otherCost['autre_frais_id'],
        "name" => $otherCost['nom'],
        "price" => $otherCost['montant'],
        "facture_id" => $otherCost['id_facture']
    ];
}


if (empty($result)) {
    returnError(404, 'No estimates found');
    return;
}

echo json_encode($result);
