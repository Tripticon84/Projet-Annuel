<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/fees.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, true, true);

if (!isset($_GET['id'])) {
    returnError(400, 'Missing id parameter');
    return;
}

$id = intval($_GET['id']);
$frais = getFraisById($id);

if (!$frais) {
    returnError(404, 'Frais not found');
    return;
}

echo json_encode($frais);
