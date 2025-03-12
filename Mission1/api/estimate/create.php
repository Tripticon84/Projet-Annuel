<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();


if (validateMandatoryParams($data, ['date_debut', 'date_fin', 'statut', 'montant', 'is_contract', 'id_societe'])) {

    // Vérifier l'email n'existe pas
    $estimate = getEmployeeByEmail($data['email']);
    if (!empty($employee)) {
        returnError(400, 'Email already exists');
        return;
    }

    // Vérifier l'username n'existe pas
    $employee = getEmployeeByUsername($data['username']);
    if (!empty($employee)) {
        returnError(400, 'Username already exists');
        return;
    }

    // Vérifier le telephone n'existe pas
    $employee = getEmployeeByTelephone($data['telephone']);
    if (!empty($employee)) {
        returnError(400, 'Telephone already exists');
        return;
    }

    // Vérification de la longueur du mot de passe
    if (strlen($data['password']) < 8) {
         returnError(400, 'Password must be at least 8 characters long');
        return;
    }

    $newEstimateId = createEstimate($data['date_debut'], $data['date_fin'], $data['statut'], $data['montant'], $data['is_contract'], $data['id_societe']);

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
