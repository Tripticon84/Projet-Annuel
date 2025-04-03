<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/association.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, true, false);


$associations = getAllAssociations();

$result = []; // Initialize the result array

foreach ($associations as $association) {
    $result[] = [
        "id" => $association['association_id'],
        "name" => $association['name'],
        "description" => $association['description'],
    ];
}

echo json_encode($result);
