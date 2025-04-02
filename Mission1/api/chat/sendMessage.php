<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, "Méthode non autorisée");
}

$data = getBody();

if (!validateMandatoryParams($data, ['salon_id', 'collaborateur_id', 'message'])) {
    returnError(400, "L'ID du salon, l'ID du collaborateur et le message sont requis");
}

$salon_id = $data['salon_id'];
$collaborateur_id = $data['collaborateur_id'];
$message = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');
$timestamp = time();

$result = saveMessage($salon_id, $collaborateur_id, $message, $timestamp);

if ($result) {
    returnSuccess(['message' => 'Message envoyé avec succès'], 201);
} else {
    returnError(500, "Erreur lors de l'envoi du message");
}
