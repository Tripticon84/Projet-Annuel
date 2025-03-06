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

function returnSuccess($data, $code = 200) {
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

function makeApiRequest(string $url, array $postData = null): array
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "localhost/" . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = 'Erreur cURL : ' . curl_error($ch);
        curl_close($ch);
        returnError(500, $error);
    } else {
        $data = json_decode($response, true);
        curl_close($ch);
        return $data;
    }
}
