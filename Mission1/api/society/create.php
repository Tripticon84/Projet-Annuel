<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/society.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();
$nom = $data['nom'];
$email = $data['email'];
$adresse = $data['adresse'];
$contact_person = $data['contact_person'];
$password = hashPassword($data['password']);
$telephone = $data['telephone'];


if (validateMandatoryParams($data, ['nom', 'email', 'adresse', 'contact_person', 'password', 'telephone'])) {

    // Vérifier l'email n'existe pas
    $society = getSocietyByEmail($email);
    if (!empty($society)) {
        returnError(400, 'society already exists');
        return;
    }

    // Vérifier le telephone n'existe pas
    $society = getSocietyByTelephone($telephone);
    if (!empty($society)) {
        returnError(400, 'Telephone already exists');
        return;
    }

    // Vérification de la longueur du mot de passe
    if (strlen($data['password']) < 12) {
         returnError(400, 'Password must be at least 12 characters long');
        return;
    }

    $newSocietyId = createSociety($nom, $email, $adresse, $contact_person, $password, $telephone);

    if (!$newSocietyId) {
        returnError(500, 'Could not create the society');
        return;
    }

    echo json_encode(['id' => $newSocietyId]);
    http_response_code(201);
    exit;
} else {
    returnError(412, 'Mandatory parameters: nom, email, adresse, contact_person, password, telephone');
}
?>




