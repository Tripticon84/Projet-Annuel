<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


function createActivity(string $nom,string $type,$date,string $place,int $id_devis,int $id_prestataire)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO activite (nom, type, date, lieu, id_devis, id_prestataire) VALUES (:nom, :type, :date, :lieu, :id_devis, :id_prestataire)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'nom' => $nom,
        'type' => $type,
        'date' => $date,
        'lieu' => $place,
        'id_devis' => $id_devis,
        'id_prestataire' => $id_prestataire
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function deleteActivity(int $id)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE activite SET desactivate = 1 WHERE activite_id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "id" => $id
    ]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteEmployee(int $id)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE collaborateur SET desactivate = 1 WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    return $res;
}

function updateActivity(string $nom = null, string $type = null, $date = null, $id_prestataire = null, $place = null, $id_devis = null, int $activite_id, $desactivate = null)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE activite SET ";
    $params = [
        "id" => $activite_id
    ];
    $coma = "";
    if ($nom !== null) {
        $sql .= "nom = :nom";
        $params['nom'] = $nom;
        $coma = ",";
    }
    if ($type !== null) {
        $sql .= $coma . "type = :type";
        $params['type'] = $type;
        $coma = ",";
    }
    if ($date !== null) {
        $sql .= $coma . "date = :date";
        $params['date'] = $date;
        $coma = ",";
    }
    if ($id_prestataire !== null) {
        $sql .= $coma . "id_prestataire = :id_prestataire";
        $params['id_prestataire'] = $id_prestataire;
        $coma = ",";
    }
    if ($place !== null) {
        $sql .= $coma . "lieu = :lieu";
        $params['lieu'] = $place;
        $coma = ",";
    }
    if ($id_devis !== null) {
        $sql .= $coma . "id_devis = :id_devis";
        $params['id_devis'] = $id_devis;
        $coma = ",";
    }
    if ($desactivate !== null) {
        $sql .= $coma . "desactivate = :desactivate";
        $params['desactivate'] = $desactivate;
    }

    
    $sql .= " WHERE activite_id = :id";

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getActivityById($id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, lieu, id_devis FROM activite WHERE activite_id = :id";
    $query = $connection->prepare($sql);
    $res = $query->execute(['id' => $id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllActivity($limit = null, $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, lieu, id_devis FROM activite";
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


