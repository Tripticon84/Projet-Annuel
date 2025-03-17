<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/estimate.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';


header('Content-Type: application/json');

if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, false, true);

$data = getBody();

$devis_id = $data['devis_id'];
$date_debut = isset($data['date_debut']) ? $data['date_debut'] : null;
$date_fin = isset($data['date_fin']) ? $data['date_fin'] : null;
$statut = isset($data['statut']) ? $data['statut'] : null;
$montant = isset($data['montant']) ? $data['montant'] : null;
$is_contract = !empty($data['is_contract']) ? $data['is_contract'] : null;
$id_societe = isset($data['id_societe']) ? $data['id_societe'] : null;
$fichier = isset($data['fichier']) ? $data['fichier'] : null;

//verifier qu un champ est fourni pour la mise a jour
if ($date_debut === null && $date_fin === null && $statut === null && $montant === null && $is_contract === null && $id_societe === null) {
    returnError(400, 'No data provided for update');
    return;
}

// Vérifier l'id existe
$estimate = getEstimateById($devis_id);

if (empty($estimate)) {
    returnError(400, 'estimate does not exist');
    return;
}

if ($is_contract != null && $is_contract != 1 && $is_contract != 0) {
    returnError(400, 'is_contract must be 0 or 1');
    return;
}

if ($id_societe != null && !is_numeric($id_societe)) {
    returnError(400, 'id_societe must be an integer');
    return;
}

if ($montant != null && !is_numeric($montant)) {
    returnError(400, 'montant must be a number');
    return;
}

if ($date_debut != null && !DateTime::createFromFormat('Y-m-d', $date_debut)) {
    returnError(400, 'date_debut must be a date');
    return;
}

if ($date_fin != null && !DateTime::createFromFormat('Y-m-d', $date_fin)) {
    returnError(400, 'date_fin must be a date');
    return;
}

if ($date_debut != null && $date_fin != null && $date_debut > $date_fin) {
    returnError(400, 'date_debut must be before date_fin');
    return;
}

if ($id_societe != null && !getSocietyById($id_societe)) {
    returnError(400, 'id_societe does not exist');
    return;
}

if ($statut != null && $statut !== 'refusé' && $statut !== 'accepté' && $statut !== 'envoyé' && $statut !== 'brouillon') {
    returnError(400, 'statut must be refusé, accepté, envoyé or brouillon');
    return;
}

$res = updateEstimate($date_debut, $date_fin, $statut, $montant, $is_contract, $id_societe,$fichier, $devis_id);


if (!$res) {
    returnError(500, 'Could not update the company');
    return;
}else{
    echo json_encode(['id' => $devis_id]);
    http_response_code(200);
}
