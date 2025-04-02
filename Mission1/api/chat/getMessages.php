<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

// Utilisation de methodIsAllowed()
if (!methodIsAllowed('read')) {
    returnError(405, "Méthode non autorisée");
}

// Validation des paramètres obligatoires
if (!validateMandatoryParams($_GET, ['salon_id'])) {
    returnError(400, "L'ID du salon est requis");
}

$salon_id = $_GET['salon_id'];
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$messages = getMessages($salon_id, $limit, $offset);

returnSuccess($messages);
