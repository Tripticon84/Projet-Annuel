<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createEstimate(DateTime $date_debut, DateTime $date_fin, string $statut, float $montant, int $is_contract, int $id_societe)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO devis (date_debut, date_fin, statut, montant, is_contract, id_societe) VALUES (:date_debut, :date_fin, :statut, :montant, :is_contract, :id_societe)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'statut' => $statut,
        'montant' => $montant,
        'is_contract' => $is_contract,
        'id_societe' => $id_societe
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateEstimate(DateTime $date_debut, DateTime $date_fin, string $statut = null, float $montant = null, int $is_contract = null, int $id_societe = null)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE devis SET date_debut = :date_debut, date_fin = :date_fin";
    $params = [
        'date_debut' => $date_debut,
        'date_fin' => $date_fin
    ];

    if ($statut !== null) {
        $sql .= ", statut = :statut";
        $params['statut'] = $statut;
    }
    if ($montant !== null) {
        $sql .= ", montant = :montant";
        $params['montant'] = $montant;
    }
    if ($is_contract !== null) {
        $sql .= ", is_contract = :is_contract";
        $params['is_contract'] = $is_contract;
    }
    if ($id_societe !== null) {
        $sql .= ", id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
    }

    $sql .= " WHERE id = :id";
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
    $sql = "DELETE FROM devis WHERE devis_id=:id";
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
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, is_contract, id_societe FROM devis WHERE devis_id = :id";
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
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, is_contract, id_societe FROM devis Where is_contract = 0";
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
    $sql = "SELECT date_debut, date_fin, statut, montant, is_contract, id_societe FROM devis Where is_contract = 1";
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
