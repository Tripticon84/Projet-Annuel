<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/society.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';


header('Content-Type: application/json');

if (!methodIsAllowed('delete')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();
$id = $data['id'];

if (validateMandatoryParams($data, ['id'])) {

    // Vérifier l'id existe
    $society = getSocietyById($id);
    if (empty($society)) {
        returnError(400, 'Company does not exist');
        return;
    }

    $res = deleteSociety($id);

    if (!$res) {
        returnError(500, 'Could not delete the Company');
        return;
    }

    echo json_encode(['id' => $id]);
    http_response_code(200);
    exit;
} else {
    returnError(412, 'Mandatory parameters: id');
}