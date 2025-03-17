<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/dao/activity.php";

header('Content-Type: application/json');

// Vérifier la méthode HTTP
if (!methodIsAllowed('update')) {
    returnError(405, 'Method not allowed');
    return;
}

// Récupérer les données de la requête
$data = getBody();

if (!isset($data['activite_id']) || empty($data['activite_id'])) {
    returnError(400, 'Activity ID is required');
    return;
}

// Récupérer l'activité par son ID
$activityId = $data['activite_id'];
$existingActivity = getActivityById($activityId);

// Vérifier si l'activité existe
if (!$existingActivity) {
    returnError(404, 'Activity not found');
    return;
}

// Variables pour stocker les résultats
$nom = isset($data['nom']) ? $data['nom'] : null;
$type = isset($data['type']) ? $data['type'] : null;
$date = isset($data['date']) ? $data['date'] : null;
$lieu = isset($data['lieu']) ? $data['lieu'] : null; // Correction de 'place' à 'lieu' pour correspondre au paramètre SQL
$id_devis = isset($data['id_devis']) ? $data['id_devis'] : null;
$id_prestataire = isset($data['id_prestataire']) ? $data['id_prestataire'] : null;
$desactivate = isset($data['desactivate']) ? $data['desactivate'] : null;

// Mise à jour en utilisant les bons noms de variables
$updateResult = updateActivity($activityId, $nom, $type, $date, $lieu, $id_prestataire, $id_devis, $desactivate);

if ($updateResult) {
    echo json_encode([
        'success' => true,
        'message' => 'Activity updated successfully'
    ]);
} else {
    returnError(500, 'An error occurred while updating the activity');
}
?>
