<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('delete')) {
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

// Vérification du token d'authentification
if (!isset($_GET['token'])) {
    returnError(401, 'Token not provided');
    return;
}
tokenVerification($_GET['token']);


if (!isset($data['id'])) {
    returnError(400, 'Missing id');
    return;
}

//verify if the admin exists
$admin = getAdminById($data['id']);
if (!$admin) {
    returnError(404, 'Admin not found');
    return;
}

$deleted = deleteAdmin($data['id']);

if ($deleted) {
    echo json_encode(['message' => 'Admin deleted']);
    return http_response_code(200);
} else {
    returnError(500, 'Failed to delete admin');
}
