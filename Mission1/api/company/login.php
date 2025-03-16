<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/company.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/stringUtils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';

header("Content-Type: application/json");

if (!methodIsAllowed('login')) {
    returnError(405, 'Method not allowed');
}

$data = getBody();

if (!validateMandatoryParams($data, ['email', 'password'])) {
    returnError(400, 'Mandatory parameters : email, password');
}

$email = trim($data['email']);
$password = trim($data['password']);
$passwordHashed = hashPassword($password);

$company = findCompanyByCredentials($email, $passwordHashed);
if (!$company) {
    returnError(401, 'Invalid credentials');
}
$companyId = $company['societe_id'];

if (!$companyId) {
    returnError(404, 'company not found');
}

$result = setCompanySession($companyId);

if (!$result) {
    returnError(500, 'An error has occurred');
}

returnSuccess(['societe_id' => $companyId]);
