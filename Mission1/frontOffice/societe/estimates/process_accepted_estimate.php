<?php
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['societe_id'])) {
    header('Location: /login.php');
    exit;
}

// Récupération des paramètres
$devis_id = isset($_GET['devis_id']) ? intval($_GET['devis_id']) : 0;
$societe_id = isset($_GET['societe_id']) ? intval($_GET['societe_id']) : 0;

// Vérification des paramètres
if ($devis_id <= 0 || $societe_id <= 0) {
    $_SESSION['error'] = "Paramètres invalides.";
    header('Location: /frontOffice/societe/estimates/estimates.php');
    exit;
}

if (!isset($_SESSION['societe_id']) || $_SESSION['societe_id'] != $societe_id) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: /frontOffice/societe/estimates/estimates.php');
    exit;
}

// Initialisation des variables de statut
$status = [
    'expense' => false,
    'invoice' => false,
    'contract' => false
];
$errors = [];

// 1. Récupération des détails du devis via getOne.php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://" . $_SERVER['HTTP_HOST'] . "/api/estimate/getOne.php?devis_id={$devis_id}");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode !== 200) {
    $_SESSION['error'] = "Impossible de récupérer les détails du devis. Code: " . $httpCode;
    header('Location: /frontOffice/societe/estimates/estimates.php');
    exit;
}

$estimate = json_decode($response, true);
if (!$estimate || json_last_error() !== JSON_ERROR_NONE) {
    $_SESSION['error'] = "Format de données invalide pour le devis.";
    header('Location: /frontOffice/societe/estimates/estimates.php');
    exit;
}

// Vérification que le devis appartient bien à la société
if ($estimate['id_societe'] != $societe_id) {
    $_SESSION['error'] = "Ce devis n'appartient pas à votre société.";
    header('Location: /frontOffice/societe/estimates/estimates.php');
    exit;
}

// 1. Conversion du devis en contrat
$contractConversionData = [
    'devis_id' => $devis_id
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://" . $_SERVER['HTTP_HOST'] . "/api/estimate/convertToContract.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); // ou POST selon l'API
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($contractConversionData));
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    if ($result && isset($result['success'])) {
        $status['contract'] = true;
    } else {
        $errors[] = "Erreur lors de la conversion en contrat: " . ($result['error'] ?? "Erreur inconnue");
    }
} else {
    $errors[] = "Erreur lors de la conversion en contrat (code: {$httpCode})";
}

// 2. Création d'un frais lié au devis
$expenseData = [
    'nom' => "Frais pour devis #{$devis_id}",
    'montant' => $estimate['montant'] ?? 0,
    'description' => "Frais automatiquement créé suite à l'acceptation du devis #{$devis_id}",
    'societe_id' => $societe_id,
    'devis_id' => $devis_id,
    'date_creation' => date('Y-m-d')
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://" . $_SERVER['HTTP_HOST'] . "/api/fees/create.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($expenseData));
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode === 200 || $httpCode === 201) {
    $result = json_decode($response, true);
    if ($result && isset($result['frais_id'])) {
        $status['expense'] = true;
    } else {
        $errors[] = "Erreur lors de la création du frais: " . ($result['error'] ?? "Erreur inconnue");
    }
} else {
    $errors[] = "Erreur lors de la création du frais (code: {$httpCode})";
}

// 3. Création d'une facture liée au devis
$today = date('Y-m-d');
$dueDate = date('Y-m-d', strtotime('+30 days'));

$invoiceData = [
    'societe_id' => $societe_id,
    'devis_id' => $devis_id,
    'date_emission' => $today,
    'date_echeance' => $dueDate,
    'montant_ttc' => $estimate['montant'] ?? 0,
    'montant_ht' => $estimate['montant_ht'] ?? 0,
    'montant_tva' => $estimate['montant_tva'] ?? 0,
    'statut' => 'émise'
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://" . $_SERVER['HTTP_HOST'] . "/api/company/addInvoice.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($invoiceData));
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode === 200 || $httpCode === 201) {
    $result = json_decode($response, true);
    if ($result && isset($result['success']) && $result['success']) {
        $status['invoice'] = true;
    } else {
        $errors[] = "Erreur lors de la création de la facture: " . ($result['error'] ?? "Erreur inconnue");
    }
} else {
    $errors[] = "Erreur lors de la création de la facture (code: {$httpCode})";
}

// 5. Préparation du message de résultat
if ($status['expense'] && $status['invoice'] && $status['contract']) {
    $_SESSION['success'] = "Le devis a été accepté avec succès ! Un frais, une facture et un contrat ont été créés.";
} elseif ($status['expense'] || $status['invoice'] || $status['contract']) {
    $_SESSION['warning'] = "Le devis a été accepté, mais certaines opérations ont échoué: " . implode(", ", $errors);
} else {
    $_SESSION['error'] = "Le devis a été accepté, mais toutes les opérations supplémentaires ont échoué: " . implode(", ", $errors);
}

// 6. Redirection vers la page des devis
header('Location: /frontOffice/societe/estimates/estimates.php');
exit;
?>

