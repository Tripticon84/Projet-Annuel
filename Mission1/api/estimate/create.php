<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, false, true);

$data = getBody();


if (validateMandatoryParams($data, ['date_debut', 'date_fin', 'statut', 'montant', 'is_contract', 'id_societe'])) {

    if (!is_numeric($data['montant'])) {
        returnError(400, 'montant must be a number');
        return;
    }
    if ($data['statut'] !== 'refusé' && $data['statut'] !== 'accepté' && $data['statut'] !== 'envoyé' && $data['statut'] !== 'brouillon') {
        returnError(400, 'statut must be refusé, accepté, envoyé or brouillon');
        return;
    }
    $company = getSocietyById($data['id_societe']);
    if (empty($company)) {
        returnError(400, 'company does not exist');
        return;
    }


    $newEstimateId = createEstimate($data['date_debut'], $data['date_fin'], $data['statut'], $data['montant'], $data['is_contract'], $data['id_societe'],$company['nom']);

    if (!$newEstimateId) {
        returnError(500, 'Could not create the estimate');
        return;
    }

    echo json_encode(['id' => $newEstimateId]);
    http_response_code(201);
    exit;

} else {
    returnError(412, 'Mandatory parameters : date_debut, date_fin, statut, montant, is_contract, id_societe');
}
