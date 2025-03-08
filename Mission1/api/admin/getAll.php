<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

$username = '';
$limit = 0;
$offset = 0;
if (isset($_GET['username'])) {
    $email = trim($_GET['username']); // Fix the parameter name
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

$admins = getAllAdmin($username, $limit, $offset);

$result = []; // Initialize the result array

foreach ($admins as $admin) {
    $result[] = [
        "id" => $admin['id'],
        "email" => $admin['username'],
    ];
}

echo json_encode($result);
