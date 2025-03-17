<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, true, false, false);


$idSociete = intval($_GET['societe_id']);
$Employees = getSocietyEmployees($idSociete);

if (!$Employees) {
    returnError(404, 'Company not found');
    return;
}

$result = []; // Initialize the result array

foreach ($Employees as $employee) {
    $result[] = [
        "collaborateur_id" => $employee['collaborateur_id'],
        "nom" => $employee['nom'],
        "prenom" => $employee['prenom'],
        "username" => $employee['username'],
        "role" => $employee['role'],
        "email" => $employee['email'],
        "telephone" => $employee['telephone']
    ];
}

if (empty($result)) {
    returnError(404, 'No employee found');
    return;
}

echo json_encode($result);
