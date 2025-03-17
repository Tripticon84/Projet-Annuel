<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


function createActivity(string $nom,string $type, $date,string $place,int $id_devis,int $id_prestataire)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO activite (nom, type, day, place, id_devis, id_prestataire) VALUES (:nom, :type, :day, :place, :id_devis, :id_prestataire)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'nom' => $nom,
        'type' => $type,
        'day' => $date,
        'place' => $place,
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
    $sql = "DELETE FROM activite WHERE activite_id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "id" => $id
    ]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function updateActivity(string $nom = null, string $type = null, $date = null, $id_prestataire = null, $place = null, $id_devis = null, int $activite_id)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE activite SET ";
    $params = [
        "id" => $activite_id
    ];

    if ($nom !== null) {
        $sql .= "nom = :nom";
        $params['nom'] = $nom;
    }
    if ($type !== null) {
        $sql .= ", type = :type";
        $params['type'] = $type;
    }

    if ($date !== null) {
        $sql .= ", date = :date";
        $params['date'] = $date;
    }
    if ($id_prestataire !== null) {
        $sql .= ", id_prestataire = :id_prestataire";
        $params['id_prestataire'] = $id_prestataire;
    }
    if ($place !== null) {
        $sql .= ", place = :place";
        $params['place'] = $place;
    }
    if ($id_devis !== null) {
        $sql .= ", id_devis = :id_devis";
        $params['id_devis'] = $id_devis;
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
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, place, id_devis FROM activite WHERE activite_id = :id";
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
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, place, id_devis FROM activite";
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

function getActivity ($activite_id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, place, id_devis FROM activite WHERE activite_id = :activite_id";
    $query = $connection->prepare($sql);
    $res = $query->execute(['activite_id' => $activite_id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
