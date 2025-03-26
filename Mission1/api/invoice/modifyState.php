<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/dao/invoice.php";

header("Content-Type: application/json");

if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

//acceptedTokens(true, false, false, false);


if (!isset($data['facture_id'])) {
    returnError(400, 'Missing id');
    return;
}

if (!isset($data['statut'])) {
    returnError(400, 'Missing state');
    return;
}


$invoice = getInvoiceById($data['facture_id']);
if (!$invoice) {
    returnError(404, 'invoice not found');
    return;
}

$modified = modifyState($data['facture_id'], $data['statut']);

if ($modified) {
    echo json_encode(['message' => 'Invoice State Modified']);
    return http_response_code(200);
} else {
    returnError(500, 'Failed to modified Invoice State');
}
