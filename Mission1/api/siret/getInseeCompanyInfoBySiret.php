<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/siret.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
header("Content-Type: application/json");

if (empty($_GET['siret']))
    returnError(400, 'Mandatory parameter : siret');

$siret = str_replace(' ', '', $_GET["siret"]);

$response = getInseeCompanyInfo("siret", $siret);

if ($response === null) returnError(503, 'No response from Insee api');

http_response_code(200);
echo json_encode($response);