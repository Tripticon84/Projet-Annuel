<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/activity.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}



$activityId = $_GET['activity_id'];

if (!isset($activityId)) {
    returnError(400, 'Missing id');
    return;
}

if (!is_numeric($activityId)) {
    returnError(400, 'Invalid parameter type. activity_id must be an integer.');
    return;
}

$activity = getActivityById($activityId);

if (!$activity) {
    returnError(404, 'Activity not found');
    return;
}

$result['activity'] = [
    "id" => $activity['activite_id'],
    "nom" => $activity['nom'],
    "type" => $activity['type'],
    "date" => $activity['day'],
    "lieu" => $activity['place'],
    "id_devis" => $activity['id_devis'],
    "id_prestataire" => $activity['id_prestataire']
];

echo json_encode($result);



