<?php
// Récupération des valeurs du GET et stockage dans des variables
$employee_count = isset($_GET['employee_count']) ? intval($_GET['employee_count']) : 0;
$additional_employees = isset($_GET['additional_employees']) ? intval($_GET['additional_employees']) : 0;
$current_cost = isset($_GET['current_cost']) ? floatval($_GET['current_cost']) : 0;
$new_annual_cost = isset($_GET['new_annual_cost']) ? floatval($_GET['new_annual_cost']) : 0;
$difference = isset($_GET['difference']) ? floatval($_GET['difference']) : 0;
$prorated_cost = isset($_GET['prorated_cost']) ? floatval($_GET['prorated_cost']) : 0;
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$months_remaining = isset($_GET['months_remaining']) ? intval($_GET['months_remaining']) : 0;

// Vérification de la présence des paramètres essentiels
if ($employee_count <= 0 || $end_date === '') {
    // Rediriger vers la page précédente ou afficher une erreur
    header('Location: new_estimate.php?error=missing_parameters');
    exit;
}

session_start();
$societe_id = $_SESSION['societe_id'];
$societe_name = $_SESSION['societe_name'];
if ($societe_id === null) {
    // Rediriger vers la page de connexion ou afficher une erreur
    header('Location: login.php?error=not_logged_in');
    exit;
}

// Préparation des données pour l'API
$data = [
    'montant_ht' => $prorated_cost,
    'id_societe' => $societe_id,
    'statut' => 'envoyé',
    'date_debut' => $start_date,
    'date_fin' => $end_date,
    'is_contract' => 0 // Indique qu'il s'agit d'un devis
];

// Conversion des données en format JSON
$data_json = json_encode($data);

// Construction de l'URL correcte pour l'API
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/api/estimate/create.php';

// Initialisation de cURL
$ch = curl_init();

// Configuration de la requête cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactiver la vérification SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);     // Désactiver la vérification de l'hôte
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_json)
]);

// Exécution de la requête
$response = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Fermeture de la session cURL
curl_close($ch);

// Traitement de la réponse
if ($error) {
    // Gestion de l'erreur
    echo "Erreur cURL : " . $error;
} elseif ($http_code != 201) {
    // La création a échoué
    $response_data = json_decode($response, true);
    $error_message = isset($response_data['message']) ? $response_data['message'] : 'Erreur inconnue';
    echo "Erreur lors de la création du devis: " . $error_message;
} else {
    // La création a réussi
    $response_data = json_decode($response, true);
    $estimate_id = $response_data['id'];
    
    // Redirection vers une page de confirmation avec tous les paramètres nécessaires
    header("Location: estimate_confirmation.php?id=" . $estimate_id . 
           "&employee_count=" . $employee_count .
           "&additional_employees=" . $additional_employees .
           "&prorated_cost=" . $prorated_cost .
           "&start_date=" . urlencode($start_date) .
           "&end_date=" . urlencode($end_date));
    exit;
}
?>

