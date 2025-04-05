<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/activity.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/place.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, false, false, false);

$data = getBody();

if (validateMandatoryParams($data, ['nom', 'type', 'date', 'lieu', 'id_devis','id_prestataire'])) {

    if (!is_numeric($data['id_devis']) || !is_numeric($data['id_prestataire'])) {
        returnError(400, 'Invalid parameter type. id_devis and id_prestataire must be integers.');
        return;
    }

    if (!is_string($data['nom']) || !is_string($data['type']) || !is_string($data['lieu'])) {
        returnError(400, 'Invalid parameter type. nom, type and lieu must be strings.');
        return;
    }

    $estimate = getEstimateById($data['id_devis']);
    if (!$estimate) {
        returnError(404, 'Estimate not found');
        return;
    }


    $provider = getProviderById($data['id_prestataire']);
    if (!$provider) {
        returnError(404, 'Provider not found');
        return;
    }

    $place = getPlaceById($data['id_lieu']);
    if (!$place) {
        returnError(404, 'Place not found');
        return;
    }

    $name = $data['nom'];
    $type = $data['type'];
    $date = $data['date'];
    $place = $data['lieu'];
    $id_devis = $data['id_devis'];
    $id_prestataire = $data['id_prestataire'];
    $place = $data['id_lieu'] ;

    $newActivityId = createActivity($name, $type, $date, $place, $id_devis, $id_prestataire, $place);

    if (!$newActivityId) {
        // Log the error for debugging
        error_log("Failed to create activity: " . print_r(error_get_last(), true));
        returnError(500, 'Could not create the activity. Database operation failed.');
        return;
    }

    echo json_encode(['activity_id' => $newActivityId]);
    http_response_code(201);
}else{
    returnError(400, 'Missing required parameters');
    return;
}
