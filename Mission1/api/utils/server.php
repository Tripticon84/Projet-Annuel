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


function acceptedTokens($admin = true, $company = false, $employee = false, $provider = false) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/admin.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/provider.php';

    $debug = false;
    if ($debug) {
        return;
    }

    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
    } elseif (isset($headers['authorization'])) {
        $token = str_replace('Bearer ', '', $headers['authorization']);
    } else {
        returnError(401, 'Unauthorized: Missing token');
        return;
    }

    $adminUser = getAdminByToken($token);
    if ($adminUser) {
        if (!$admin) {
            returnError(403, 'Unauthorized: Admin access not allowed');
            return;
        }
        $expirationDate = getExpirationByToken($token);
        if ($expirationDate) {
            $expiration = new DateTime($expirationDate['expiration']);
            if ($expiration < new DateTime()) {
                returnError(401, 'Unauthorized: Token expired');
                return;
            }
        } else {
            returnError(500, 'Internal Server Error: Unable to retrieve expiration date');
            return;
        }
        return; // Admin is authorized
    }

    $employeeUser = getEmployeeByToken($token);
    if ($employeeUser) {
        if (!$employee) {
            returnError(403, 'Unauthorized: Employee access not allowed');
            return;
        }
        $expirationDate = getEmployeeExpirationByToken($token);
        if ($expirationDate) {
            $expiration = new DateTime($expirationDate['expiration']);
            if ($expiration < new DateTime()) {
                returnError(401, 'Unauthorized: Token expired');
                return;
            }
        } else {
            returnError(500, 'Internal Server Error: Unable to retrieve expiration date');
            return;
        }
        return; // Employee is authorized
    }

    $companyUser = getCompanyByToken($token);
    if ($companyUser) {
        if (!$company) {
            returnError(403, 'Unauthorized: Company access not allowed');
            return;
        }
        $expirationDate = getCompanyExpirationByToken($token);
        if ($expirationDate) {
            $expiration = new DateTime($expirationDate['expiration']);
            if ($expiration < new DateTime()) {
                returnError(401, 'Unauthorized: Token expired');
                return;
            }
        } else {
            returnError(500, 'Internal Server Error: Unable to retrieve expiration date');
            return;
        }
        return; // Company is authorized
    }

    $providerUser = getProviderByToken($token);
    if ($providerUser) {
        if (!$provider) {
            returnError(403, 'Unauthorized: Provider access not allowed');
            return;
        }
        $expirationDate = getProviderExpirationByToken($token);
        if ($expirationDate) {
            $expiration = new DateTime($expirationDate['expiration']);
            if ($expiration < new DateTime()) {
                returnError(401, 'Unauthorized: Token expired');
                return;
            }
        } else {
            returnError(500, 'Internal Server Error: Unable to retrieve expiration date');
            return;
        }
        return;
    }

    // Si on arrive ici, le jeton n'est valide pour aucun utilisateurs
    returnError(401, 'Unauthorized: Invalid token');
}
