<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

// Vérification du token d'authentification
if (!isset($_GET['token'])) {
    returnError(401, 'Token not provided');
    return;
}
tokenVerification($_GET['token']);

// Vérification de l'ID de l'employee
if (!isset($_GET['id'])) {
    returnError(400, 'Employee ID not provided');
    return;
}

$employeeId = intval($_GET['id']);
$employee = getEmployee($employeeId);

if (!$employee) {
    returnError(404, 'Employee not found');
    return;
}

$result = [
    "collaborateur_id" => $employee['collaborateur_id'],
    "nom" => $employee['nom'],
    "prenom" => $employee['prenom'],
    "username" => $employee['username'],
    "role" => $employee['role'],
    "email" => $employee['email'],
    "telephone" => $employee['telephone'],
    "id_societe" => $employee['id_societe'],
];

echo json_encode($result);
http_response_code(200);
