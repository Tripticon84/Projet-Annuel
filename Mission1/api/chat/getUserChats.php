<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, "Méthode non autorisée");
}

// Validation des paramètres obligatoires
if (!validateMandatoryParams($_GET, ['collaborateur_id'])) {
    returnError(400, "L'ID du collaborateur est requis");
}

$collaborateur_id = $_GET['collaborateur_id'];
$chats = getUserChats($collaborateur_id);

returnSuccess($chats);
