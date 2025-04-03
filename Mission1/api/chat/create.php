<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

// Vérification des tokens d'authentification
acceptedTokens(true, false, false, false); // Admin


if (!methodIsAllowed('create')) {
    returnError(405, "Méthode non autorisée");
}

$data = getBody();

// Validation des paramètres obligatoires
if (!validateMandatoryParams($data, ['nom', 'description'])) {
    returnError(400, "Le nom et la description sont requis");
}

$nom = $data['nom'];
$description = $data['description'];

$chat_id = createChat($nom, $description);

if ($chat_id) {
    returnSuccess([
        'message' => 'Salon créé avec succès',
        'salon_id' => $chat_id
    ], 201);
} else {
    returnError(500, "Erreur lors de la création du salon");
}
