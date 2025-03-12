<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

if (!methodIsAllowed('delete')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();



// // Vérification du token d'authentification
// if (!isset($_GET['token'])) {
//     returnError(401, 'Token not provided');
//     return;
// }
// tokenVerification($_GET['token']);


if (!isset($data['prestataire_id'])) {
    returnError(400, 'Missing id');
    return;
}


$provider = getProviderById($data['prestataire_id']);
if (!$provider) {
    returnError(404, 'Provider not found');
    return;
}

$deleted = deleteProvider($data['prestataire_id']);

if ($deleted) {
    echo json_encode(['message' => 'Prestataire deleted']);
    return http_response_code(200);
} else {
    returnError(500, 'Failed to delete Prestataire');
}
