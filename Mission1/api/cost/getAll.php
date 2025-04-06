<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/cost.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, true, true);

$societyId = null;
$name = null;
$limit = null;
$offset = null;

if (isset($_GET['society_id'])) {
    $societyId = intval($_GET['society_id']);
    if ($societyId < 1) {
        returnError(400, 'society_id must be a positive number');
    }
}

if (isset($_GET['name'])) {
    $name = trim($_GET['name']);
}

if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
    if ($limit < 1) {
        returnError(400, 'Limit must be a positive and non-zero number');
    }
}

if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    if ($offset < 0) {
        returnError(400, 'Offset must be a positive number');
    }
}

$costs = getAllCosts($societyId, $name, $limit, $offset);

if (!$costs) {
    returnError(404, 'No costs found');
    return;
}

$result = [];
foreach ($costs as $cost) {
    $result[] = [
        "id" => $cost['id'],
        "name" => $cost['name'],
        "amount" => $cost['amount'],
        "invoice_id" => $cost['invoice_id'],
        "created_at" => $cost['created_at']
    ];
}

echo json_encode($result);
