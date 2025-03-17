<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, false, false, false);

$username = '';
$limit = null;
$offset = null;
$id_societe = null;

if (isset($_GET['username'])) {
    $username = trim($_GET['username']); // Fix the parameter name
}
if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
    if ($limit < 1) {
        returnError(400, 'Limit must be a positive and non zero number');
    }
}
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    if ($offset < 0) {
        returnError(400, 'Offset must be a positive number');
    }
}
if (isset($_GET['id_societe'])) {
    $offset = intval($_GET['id_societe']);
    if ($offset < 0) {
        returnError(400, 'id_societe must be a positive number');
    }
}

$employees = getAllEmployees($username, $limit, $offset, $id_societe);

$result = []; // Initialize the result array

foreach ($employees as $employee) {
    $result[] = [
        "collaborateur_id" => $employee['collaborateur_id'],
        "nom" => $employee['nom'],
        "prenom" => $employee['prenom'],
        "username" => $employee['username'],
        "role" => $employee['role'],
        "email" => $employee['email'],
        "telephone" => $employee['telephone'],
        "id_societe" => $employee['id_societe'],
        "date_creation" => $employee['date_creation'],
        "date_activite" => $employee['date_activite'],
    ];
}

echo json_encode($result);
