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

// Vérification du token d'authentification
if (!isset($_GET['token'])) {
    returnError(401, 'Token not provided');
    return;
}
tokenVerification($_GET['token']);

if (validateMandatoryParams($data, ['username', 'password'])) {
    // Vérification si l'admin existe déjà
    $admin = getAdminByUsername($data['username']);
    if (!empty($admin)) {
        returnError(400, 'Admin already exist');
        return;
    }

    // Vérification de la longueur du mot de passe
    if (strlen($data['password']) < 8) {
         returnError(400, 'Password must be at least 8 characters long');
        return;
    }

    // Création de l'administrateur
    $newAdminId = createAdmin($data['username'], $data['password']);

    if (!$newAdminId) {
        returnError(500, 'Could not create the admin');
        return;
    }

    echo json_encode(['id' => $newAdminId]);
    http_response_code(201);
    exit();

} else {
    returnError(412, 'Mandatory parameters: username, password');
}
