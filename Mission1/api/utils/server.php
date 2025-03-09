<?php


function methodIsAllowed(string $action): bool
{
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($action) {
        case 'update':
            return $method == 'PATCH';
        case 'create':
            return $method == 'PUT';
        case 'read':
            return $method == 'GET';
        case 'delete':
            return $method == 'DELETE';
        case 'login':
            return $method == 'POST';
        default:
            return false;
    }
}

function getBody(): array
{
    $body = file_get_contents('php://input');
    return json_decode($body, true);
}

function returnError(int $code, string $message)
{
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit();
}

function returnSuccess($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data);
}


function validateMandatoryParams(array $data, array $mandatoryParams): bool
{
    foreach ($mandatoryParams as $param) {
        if (!isset($data[$param])) {
            return false;
        }
    }
    return true;
}

function tokenVerification($token)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/admin.php';


    if (!isset($token)) {
        returnError(401, 'Unauthorized: Missing token');
        return;
    }
    $adminToken = getAdminByToken($token);
    if (!$adminToken) {
        returnError(401, 'Unauthorized: Invalid token');
        return;
    }
    $expirationDate = getExpirationByToken($token);
    if ($expirationDate) {
        $expiration = new DateTime($expirationDate['expiration']);
        if ($expiration < new DateTime()) {
            returnError(401, 'Unauthorized: Token expired');
            return;
        }
    }
}
