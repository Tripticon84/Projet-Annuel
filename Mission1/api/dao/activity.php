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

function updateActivity($activite_id, $nom = null, $type = null, $date = null, $id_prestataire, $place = null, $id_devis = null, $desactivate = null)
{
    $db = getDatabaseConnection();
    $params = ['id' => $activite_id];
    $setFields = [];

    if ($nom !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }

    if ($type !== null) {
        $setFields[] = "type = :type";
        $params['type'] = $type;
    }

    if ($date !== null) {
        $setFields[] = "date = :date";
        $params['date'] = $date;
    }

    if ($id_prestataire !== null) {
        $setFields[] = "id_prestataire = :id_prestataire";
        $params['id_prestataire'] = $id_prestataire;
    }

    if ($place !== null) {
        $setFields[] = "lieu = :lieu";
        $params['lieu'] = $place;
    }

    if ($id_devis !== null) {
        $setFields[] = "id_devis = :id_devis";
        $params['id_devis'] = $id_devis;
    }

    if ($desactivate !== null) {
        $setFields[] = "desactivate = :desactivate";
        $params['desactivate'] = $desactivate;
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE activite SET " . implode(", ", $setFields) . " WHERE activite_id = :id";
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

function getAllActivity($limit = null, $offset = null, $nom = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT activite_id, nom, type, date, id_prestataire, lieu, id_devis FROM activite WHERE 1=1";
    $params = [];

    // Ajout du filtre par nom si spécifié
    if ($nom !== null && $nom !== '') {
        $sql .= " AND nom LIKE :nom";
        $params['nom'] = "%" . $nom . "%";
    }

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}
