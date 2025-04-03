<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, false, false, false);

$data = getBody();


if (validateMandatoryParams($data, ['date_emission', 'date_echeance', 'statut', 'methode_paiement', 'id_devis', 'id_prestataire'])) {


    if (!is_numeric($data['montant'])) {
        returnError(400, 'montant must be a number');
        return;
    }
    if (!is_numeric($data['montant_tva'])) {
        returnError(400, 'montant_tva must be a number');
        return;
    }
    if (!is_numeric($data['montant_ht'])) {
        returnError(400, 'montant_ht must be a number');
        return;
    }


    if ($data['statut'] != 'Attente' && $data['statut'] != 'Payee' && $data['statut'] != 'Annulee') {
        returnError(400, 'statut must be Attente, Payee or Annulee');
        return;
    }

    $estimate = getEstimateById($data['id_devis']);
    if (empty($estimate)) {
        returnError(400, 'estimate does not exist');
        return;
    }

    if($estimate['is_contract'] != 0){
        returnError(400, 'estimate must be accepted');
        return;
    }

    if(empty($estimate['montant_ht'])){
        returnError(400, 'estimate must have a montant_ht');
        return;
    }

    if(empty($estimate['montant_tva'])){
        returnError(400, 'estimate must have a montant_tva');
        return;
    }

    if(empty($estimate['montant'])){
        returnError(400, 'estimate must have a montant');
        return;
    }



    $provider = getProviderById($data['id_prestataire']);
    if (empty($provider)){
        returnError(400, 'provider does not exist');
        return;
    }

    if ($date_emission > $date_echeance) {
        returnError(400, 'date_emission must be before date_echeance');
        return;
    }


    $newInvoiceId = createInvoice($data['date_emission'], $data['date_echeance'], $estimate['montant'], $estimate['montant_tva'], $estimate
    ['montant_ht'], $data['statut'], $data['methode_paiement'], $data['id_devis'], $data['id_prestataire']);

    if (!$newInvoiceId) {
        returnError(500, 'Could not create the invoice');
        return;
    }

    echo json_encode(['id' => $newInvoiceId]);
    http_response_code(201);
    exit;

} else {
    returnError(412, 'Mandatory parameters : date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire');
}
