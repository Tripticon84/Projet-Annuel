<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, "Méthode non autorisée");
}

if (!validateMandatoryParams($_GET, ['salon_id'])) {
    returnError(400, "L'ID du salon est requis");
}

$salon_id = $_GET['salon_id'];
$count = isset($_GET['count']) ? intval($_GET['count']) : 20;

$messages = getLatestMessages($salon_id, $count);

returnSuccess($messages);
