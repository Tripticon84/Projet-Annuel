<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

if (!methodIsAllowed('update')) {
    returnError(405, "Méthode non autorisée");
}

$data = getBody();

if (!validateMandatoryParams($data, ['salon_id'])) {
    returnError(400, "L'ID du salon est requis");
}

$salon_id = $data['salon_id'];
$nom = isset($data['nom']) ? $data['nom'] : null;
$description = isset($data['description']) ? $data['description'] : null;

$result = updateChat($salon_id, $nom, $description);

if ($result !== null) {
    if ($result > 0) {
        returnSuccess(['message' => 'Salon mis à jour avec succès']);
    } else {
        returnSuccess(['message' => 'Aucune modification effectuée']);
    }
} else {
    returnError(500, "Erreur lors de la mise à jour du salon");
}
