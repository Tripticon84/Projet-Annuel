<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createEstimate($date_debut,$date_fin, string $statut, float $montant, $is_contract, int $id_societe, $company_name)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO devis (date_debut, date_fin, statut, montant, is_contract,fichier, id_societe) VALUES (:date_debut, :date_fin, :statut, :montant, :is_contract, :fichier, :id_societe)";
    $stmt = $db->prepare($sql);
    if ($is_contract == 1) {
        $fichier = '/contract/' .$company_name.'/'. $date_debut .'_'.$date_fin . '/';
    
    }else{
        $fichier = '/estimate/' .$company_name.'/'. $date_debut .'_'.$date_fin . '/';
    }
    $res = $stmt->execute([
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'statut' => $statut,
        'montant' => $montant,
        'is_contract' => $is_contract,
        'id_societe' => $id_societe,
        'fichier' => $fichier
        
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateEstimate( $date_debut = null,  $date_fin = null , string $statut = null, float $montant = null, int $is_contract = null, int $id_societe = null, string $fichier = null, int $devis_id)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE devis SET ";
    $params = [
        "id" => $devis_id
    ];
    $coma = "";
    if ($date_debut !== null) {
        $sql .= "date_debut = :date_debut";
        $params['date_debut'] = $date_debut;
        $coma = ",";
    }
    if ($date_fin !== null) {
        $sql .= $coma . "date_fin = :date_fin";
        $params['date_fin'] = $date_fin;
        $coma = ",";
    }
    if ($statut !== null) {
        $sql .= $coma . "statut = :statut";
        $params['statut'] = $statut;
        $coma = ",";
    }
    if ($montant !== null) {
        $sql .= $coma . "montant = :montant";
        $params['montant'] = $montant;
        $coma = ",";
    }
    if ($is_contract !== null) {
        $sql .= $coma . "is_contract = :is_contract";
        $params['is_contract'] = $is_contract;
        $coma = ",";
    }
    if ($id_societe !== null) {
        $sql .= $coma . "id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
        $coma = ",";
    }
    if ($fichier !== null) {
        $sql .= $coma . "fichier = :fichier";
        $params['fichier'] = $fichier;
        $coma = ",";
    }
    $sql .= " WHERE devis_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}


function deleteEstimate(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM devis WHERE devis_id=:devis_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "devis_id" => $id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getEstimateById($id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, is_contract, fichier, id_societe FROM devis WHERE devis_id = :id ";
    $query = $connection->prepare($sql);
    $res = $query->execute(['id' => $id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}


function getAllEstimate(int $limit = null, int $offset = null)        //tout les params sont optionnels: le premier pour filtrer par username, le deuxième pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, is_contract, fichier, id_societe FROM devis Where is_contract = 0";
    $params = [];

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);  // Seuls les paramètres username seront utilisés

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllContract(int $limit = null, int $offset = null)        //tout les params sont optionnels: le premier pour filtrer par username, le deuxième pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, is_contract, fichier, id_societe FROM devis Where is_contract = 1";
    $params = [];

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);  // Seuls les paramètres username seront utilisés

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}
