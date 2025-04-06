<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/hashPassword.php';

// Vérifier si les étapes précédentes ont été complétées
if (!isset($_SESSION['company_data']) || !isset($_POST['plan'])) {
    $_SESSION['register_errors'] = ['Une erreur est survenue : données manquantes. Veuillez recommencer l\'inscription.'];
    header('Location: register.php');
    exit();
}

// Récupérer les données
$company = $_SESSION['company_data'];
$plan = $_POST['plan'];

// Validation supplémentaire des données
$requiredFields = ['nom', 'siret', 'adresse', 'email', 'contact_person', 'telephone', 'password'];
$errors = [];

foreach ($requiredFields as $field) {
    if (empty($company[$field])) {
        $errors[] = "Le champ '$field' est requis.";
    }
}

if (!in_array($plan, ['starter', 'basic', 'premium'])) {
    $errors[] = "Le plan sélectionné n'est pas valide.";
}

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    header('Location: register.php');
    exit();
}

// Construction de la requête à l'API
$apiUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/api/company/create.php';
$data = [
    'nom' => $company['nom'],
    'siret' => $company['siret'],
    'adresse' => $company['adresse'],
    'email' => $company['email'],
    'contact_person' => $company['contact_person'],
    'telephone' => $company['telephone'],
    'password' => $company['password']
];

// Envoi de la requête API
$ch = curl_init($apiUrl);
if ($ch === false) {
    $_SESSION['register_errors'] = ['Erreur d\'initialisation cURL. Veuillez réessayer ultérieurement.'];
    header('Location: register.php');
    exit();
}

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout de connexion à 10 secondes
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout global à 30 secondes

$response = curl_exec($ch);

// Vérifier les erreurs cURL
if ($response === false) {
    $errorMessage = 'Erreur cURL: ' . curl_error($ch);
    error_log($errorMessage); // Journalisation de l'erreur
    $_SESSION['register_errors'] = ['Erreur de communication avec le serveur. Veuillez réessayer ultérieurement.'];
    curl_close($ch);
    header('Location: register.php');
    exit();
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$responseData = json_decode($response, true);

// Journalisation des réponses d'erreur pour le débogage
if ($httpCode != 201) {
    error_log("Erreur API création société - Code: $httpCode - Réponse: " . print_r($responseData, true));
}

// Traitement de la réponse
if ($httpCode == 201) {
    // Succès - rediriger vers une page de confirmation
    $_SESSION['registration_success'] = true;
    
    // Nettoyage des données de session
    unset($_SESSION['company_data']);
    unset($_SESSION['register_errors']);
    unset($_SESSION['register_data']);
    
    header('Location: confirmation.php');
    exit();
} else {
    // Traitement des codes d'erreur HTTP spécifiques
    switch ($httpCode) {
        case 400:
            $errorMsg = $responseData['error'] ?? 'Les données fournies sont invalides.';
            break;
        case 401:
        case 403:
            $errorMsg = 'Authentification requise ou accès refusé.';
            break;
        case 404:
            $errorMsg = 'Le service demandé est indisponible.';
            break;
        case 409:
            $errorMsg = 'Cette entreprise existe déjà dans notre système.';
            break;
        case 500:
            $errorMsg = 'Erreur interne du serveur. Veuillez réessayer ultérieurement.';
            break;
        default:
            $errorMsg = $responseData['error'] ?? 'Une erreur inattendue est survenue lors de la création du compte.';
    }
    
    $_SESSION['register_errors'] = [$errorMsg];
    $_SESSION['register_data'] = [
        'nom' => $company['nom'],
        'siret' => $company['siret'],
        'adresse' => $company['adresse'],
        'email' => $company['email'],
        'contact_person' => $company['contact_person'],
        'telephone' => $company['telephone']
    ];
    
    header('Location: register.php');
    exit();
}
?>
