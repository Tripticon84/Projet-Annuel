<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/evaluation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

//acceptedTokens(true, false, false, false);


$data = getBody();
$note = $data['note'];
$commentaire = $data['commentaire'];
$collaborateur_id = $data['collaborateur_id'];
$date_creation = date('Y-m-d H:i:s'); // Get the current date and timeS
$id_prestataire = $data['prestataire_id']; // Optional field

if (validateMandatoryParams($data, ['note', 'commentaire', 'collaborateur_id'])) {

    if (!is_numeric($note) || $note < 0 || $note > 5) {
        returnError(400, 'Note must be a number between 0 and 5');
        return;
    }
    if (strlen($commentaire) > 255) {
        returnError(400, 'Commentaire must be less than 255 characters');
        return;
    }

    if (!is_numeric($collaborateur_id)) {
        returnError(400, 'collaborateur_id must be a number');
        return;
    }
    if (!getEmployee($collaborateur_id)) {
        returnError(404, 'employye not found');
        return;
    }

    $newEvaluationId = createEvaluation($note, $commentaire, $collaborateur_id, $date_creation);

    if (!$newEvaluationId) {
        // Log the error for debugging
        error_log("Failed to create evaluation: " . print_r(error_get_last(), true));
        returnError(500, 'Could not create the Evaluation. Database operation failed.');
        return;
    }

    $insert=newEvaluationInNote_prestataire($newEvaluationId,$id_prestataire);
    if (!$insert) {
        // Log the error for debugging
        error_log("Failed to create evaluation: " . print_r(error_get_last(), true));
        returnError(500, 'Could not create the Evaluation. Database operation failed.');
        return;
    }

    echo json_encode(['evaluation_id' => $newEvaluationId]);
    http_response_code(201);
} else {
    returnError(400, 'Missing required parameters');
    return;
}