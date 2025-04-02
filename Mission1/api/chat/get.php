<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

if (!methodIsAllowed('read')) {
    returnError(405, "Méthode non autorisée");
}

// Validation des paramètres obligatoires
if (!validateMandatoryParams($_GET, ['salon_id'])) {
    returnError(400, "L'ID du salon est requis");
}

$salon_id = $_GET['salon_id'];
$chat = getChat($salon_id);

if ($chat) {
    returnSuccess($chat);
} else {
    returnError(404, "Salon non trouvé");
}
