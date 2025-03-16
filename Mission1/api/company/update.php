<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';

header('Content-Type: application/json');

if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();

$id = $data['id'];
$nom = $data['nom'];
$email = $data['email'];
$adresse = $data['adresse'];
$contact_person = $data['contact_person'];
$password = !empty($data['password']) ? hashPassword($data['password']) : null;
$telephone = $data['telephone'];

if (validateMandatoryParams($data, ['id', 'nom', 'email', 'adresse', 'contact_person', 'telephone'])) {

    // Vérifier l'id existe
    $company = getSocietyById($id);
    if (empty($company)) {
        returnError(400, 'company does not exist');
        return;
    }

    // Vérifier l'email n'existe pas
    $company = getSocietyByEmail($email);
    if (!empty($company) && $company['societe_id'] != $id) {
        returnError(400, 'company already exists');
        return;
    }

    // Vérifier le telephone n'existe pas
    $company = getSocietyByTelephone($telephone);
    if (!empty($company) && $company['societe_id'] != $id) {
        returnError(400, 'Telephone already exists');
        return;
    }

    // Vérification de la longueur du mot de passe
    if ($password != null && strlen($data['password']) < 12) {
        returnError(400, 'Password must be at least 12 characters long');
        return;
    }

    $res = updateSociety($id, $nom, $email, $adresse, $contact_person, $password, $telephone);

    if (!$res) {
        returnError(500, 'Could not update the company');
        return;
    }

    echo json_encode(['id' => $id]);
    http_response_code(200);
    exit;
} else {
    returnError(412, 'Mandatory parameters: id, nom, email, adresse, contact_person, password, telephone');
}
