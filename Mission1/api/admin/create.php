<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

if (validateMandatoryParams($data, ['email', 'password'])) {
    // Vérification de l'email (optionnel, selon vos besoins)
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        returnError(400, 'Invalid email format');
        return;
    }

    // Vérification de la longueur du mot de passe (optionnel, selon vos besoins)
    if (strlen($data['password']) < 8) {
        returnError(400, 'Password must be at least 8 characters long');
        return;
    }

    // Création de l'administrateur
    $newAdminId = createAdmin($data['email'], $data['password']);

    if (!$newAdminId) {
        returnError(500, 'Could not create the admin');
        return;
    }

    echo json_encode(['id' => $newAdminId]);
    http_response_code(201);
    exit();

} else {
    returnError(412, 'Mandatory parameters: email, password');
}
