<?php

function generateInseeToken() {
    // Cette fonction génère un token d'authentification pour l'API INSEE
    // Elle utilise les identifiants client (ID et secret) fournis
    // La fonction envoie une requête POST à l'API d'authentification de l'INSEE
    // et retourne le résultat décodé du JSON (contenant le token d'accès)
    $client_insee_id = 'efOz1ibg5Nv5090b9Xn1gaFMdCga';  // a cacher derriere des variables d'environnement lors de la mise en prod (docker)
    $client_insee_secret = 'ZKx_vAXG8YTQzQByh48rPJiLRbYa';// a cacher derriere des variables d'environnement lors de la mise en prod (docker)
    
    $url = "https://api.insee.fr/token";
    $data = "grant_type=client_credentials";
    $headers = [
        "Authorization: Basic " . base64_encode("$client_insee_id:$client_insee_secret"),
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $ch = curl_init($url);  //Initialise une requête cURL vers l'URL de l'API.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //On veut récupérer la réponse sous forme de texte.
    curl_setopt($ch, CURLOPT_POST, true);  //On indique que c'est une requête POST.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //On indique les données à envoyer dans le corps de la requête.
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  //On ajoute les en-têtes HTTP à la requête.
        
    $response = curl_exec($ch);  //Exécute la requête cURL et stocke la réponse dans $response.
    curl_close($ch);  //Ferme la session cURL pour libérer les ressources.

    return json_decode($response, true);  //On décode la réponse JSON et la retourne sous forme de tableau associatif.
}

function getInseeCompanyInfo($type, $code) {
    // Cette fonction récupère les informations d'une entreprise auprès de l'API INSEE
    // Elle prend en paramètres:
    //   - $type: le type d'identifiant (siret ou siren)
    //   - $code: la valeur de l'identifiant

    // Obtention du token d'authentification
    $response_token = generateInseeToken();

    // Vérification de la présence du token d'accès
    if (empty($response_token['access_token']) && empty($response_token['error']['access_token']))
        return $response_token;
    
    elseif (!empty($response_token['access_token']))
        $token = $response_token['access_token'];
    
    else $token = $response_token['error']['access_token'];

    // Construction de l'URL pour l'API Sirene v3.11
    $url = "https://api.insee.fr/entreprises/sirene/V3.11/$type/$code";
    $headers = ["Authorization: Bearer $token"];

    // Initialisation et configuration de la requête cURL
    $ch = curl_init($url); // Initialise une requête cURL vers l'URL de l'API.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // On veut récupérer la réponse sous forme de texte.
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // On ajoute les en-têtes HTTP à la requête.
    
    // Exécution de la requête et récupération du résultat
    $response = curl_exec($ch);  // Exécute la requête cURL et stocke la réponse dans $response.
    curl_close($ch);  // Ferme la session cURL pour libérer les ressources.
    
    // Retourne les données de l'entreprise au format tableau associatif
    return json_decode($response, true);
}

function getInseeCompanyInfoBySiret($siret) {
    // Fonction simplifiée pour rechercher par SIRET
    return getInseeCompanyInfo("siret", $siret);
}

function getInseeCompanyInfoBySiren($siren) {
    // Fonction simplifiée pour rechercher par SIREN
    return getInseeCompanyInfo("siren", $siren);    
}