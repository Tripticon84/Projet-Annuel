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


if (validateMandatoryParams($data, ['date_emission', 'date_echeance', 'statut', 'methode_paiement', 'id_devis'])) {


    if ($data['statut'] != 'Attente' && $data['statut'] != 'Payee' && $data['statut'] != 'Annulee') {
        returnError(400, 'statut must be Attente, Payee or Annulee');
        return;
    }


    if($estimate['is_contract'] != 0){
        returnError(400, 'estimate must be accepted');
        return;
    }


    if ($date_emission > $date_echeance) {
        returnError(400, 'date_emission must be before date_echeance');
        return;
    }


    if (!empty($data['id_prestataire'])) {
        if (!is_numeric($data['id_prestataire'])) {
            returnError(400, 'id_prestataire must be a number');
            return;
        }
        $provider = getProviderById($data['id_prestataire']);
        if (empty($provider)) {
            returnError(400, 'provider does not exist');
            return;
        }
    } else {
        $data['id_prestataire'] = null;
    }

    $estimate = getEstimateById($data['id_devis']);
    if (empty($estimate)) {
        returnError(400, 'estimate does not exist');
        return;
    }

    $montant = $estimate['montant'];
    $montant_tva = $estimate['montant_tva'];
    $montant_ht = $estimate['montant_ht'];
    
    if (!empty($data['montant'])) {
        if (!is_numeric($data['montant'])) {
            returnError(400, 'montant must be a number');
            return;
        }
        $montant = $data['montant'];
    }
    if (!empty($data['montant_tva'])) {
        if (!is_numeric($data['montant_tva'])) {
            returnError(400, 'montant_tva must be a number');
            return;
        }
        $montant_tva = $data['montant_tva'];
    }

    if (!empty($data['montant_ht'])) {
        if (!is_numeric($data['montant_ht'])) {
            returnError(400, 'montant_ht must be a number');
            return;
        }
        $montant_ht = $data['montant_ht'];
    }
    if ($montant_tva > $montant) {
        returnError(400, 'montant_tva must be less than montant');
        return;
    }

    if ($montant_ht > $montant) {
        returnError(400, 'montant_ht must be less than montant');
        return;
    }
    if ($montant_tva + $montant_ht > $montant) {
        returnError(400, 'montant_tva + montant_ht must be less than montant');
        return;
    }

    $newInvoiceId = createInvoice($data['date_emission'], $data['date_echeance'], $montant, $montant_tva, $montant_ht, $data['statut'], $data['methode_paiement'], $data['id_devis'], $data['id_prestataire']);

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
