<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

// acceptedTokens(true, true, false, false);


$factureId = $_GET['facture_id'];

if (empty($factureId)) {
    returnError(400, 'Mandatory parameter : factureId');
}

if (!is_numeric($factureId)) {
    returnError(400, 'factureId must be a number');
}

if ($factureId < 0) {
    returnError(400, 'factureId must be a positive number');
}

if (getInvoiceById($factureId) == null) {
    returnError(404, 'Invoice not found');
}

if ($factureId) {
    $pdf= generatePDFForCompany($factureId);

    http_response_code(200);
    echo json_encode($pdf);

}else {
    returnError(500, 'No invoice found');
}
