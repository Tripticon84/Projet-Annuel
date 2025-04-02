<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

if (!methodIsAllowed('delete')) {
    returnError(405, "Méthode non autorisée");
}

$data = getBody();

// Validation des paramètres obligatoires
if (!validateMandatoryParams($data, ['salon_id', 'collaborateur_id'])) {
    returnError(400, "L'ID du salon et l'ID du collaborateur sont requis");
}

$salon_id = $data['salon_id'];
$collaborateur_id = $data['collaborateur_id'];

$result = removeUserFromChat($salon_id, $collaborateur_id);

if ($result > 0) {
    returnSuccess(['message' => 'Utilisateur retiré du salon avec succès']);
} else {
    returnError(404, "Utilisateur non trouvé dans ce salon");
}
