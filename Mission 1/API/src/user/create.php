<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/user.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();


if (validateMandatoryParams($data, ['email', 'password'])) {

    $newUserId = createUser($data['email'],$data['password']);
    if (!$newUserId) {
        returnError(500, 'Could not create the viking');
    }
    echo json_encode(['id' => $newUserId]);
    http_response_code(201);

} else {
    returnError(412, 'Mandatory parameters : name, health, attack, defense');
}

