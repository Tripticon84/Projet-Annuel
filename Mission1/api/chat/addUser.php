<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chat.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';

header('Content-Type: application/json');

// Vérification des tokens d'authentification
acceptedTokens(true, false, true, false); // Admin et employés autorisés

if (!methodIsAllowed('create')) {
    returnError(405, "Méthode non autorisée");
}

$data = getBody();

if (!validateMandatoryParams($data, ['salon_id', 'collaborateur_id'])) {
    returnError(400, "L'ID du salon et l'ID du collaborateur sont requis");
}

$salon_id = $data['salon_id'];
$collaborateur_id = $data['collaborateur_id'];

// Récupérer le token et l'utilisateur connecté
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) :
         (isset($headers['authorization']) ? str_replace('Bearer ', '', $headers['authorization']) : '');

$employeeUser = getEmployeeByToken($token);
$adminUser = getAdminByToken($token);

// Si c'est un employé (et pas un admin), vérifier qu'il s'ajoute lui-même
if ($employeeUser && !$adminUser) {
    // Un employé ne peut ajouter que lui-même
    if ($employeeUser['collaborateur_id'] != $collaborateur_id) {
        returnError(403, "Vous ne pouvez pas ajouter un autre utilisateur au salon");
    }
}

// Vérifie si le salon existe
$salon = getChat($salon_id);
if ($salon == null) {
    returnError(404, "Salon introuvable");
}

// Vérifie si le collaborateur existe
$collaborateur = getEmployee($collaborateur_id);
if ($collaborateur === null) {
    returnError(404, "Collaborateur introuvable");
}

// Vérifie si le collaborateur est déjà dans le salon
$collaborateurInSalon = getUserChats($collaborateur_id);
if ($collaborateurInSalon !== null) {
    foreach ($collaborateurInSalon as $chat) {
        if ($chat['salon_id'] == $salon_id) {
            returnError(400, "Le collaborateur est déjà dans le salon");
        }
    }
}

$result = addUserToChat($salon_id, $collaborateur_id);

if ($result > 0) {
    returnSuccess(['message' => 'Utilisateur ajouté au salon avec succès'], 201);
} else {
    returnError(500, "Erreur lors de l'ajout de l'utilisateur au salon");
}
