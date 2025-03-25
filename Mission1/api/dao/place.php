<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createPlace($adress, $city, $postalCode)
{
    
        $db = getDatabaseConnection();
        $sql = "INSERT INTO lieu (adresse, ville, code_postal) VALUES (:adress, :city, :postal_code)";
        $stmt = $db->prepare($sql);
        $res=$stmt->execute(['adress' => $adress, 'city' => $city, 'postal_code' => $postalCode]);
        if($res){
            return $db->lastInsertId();
        }
        return null;
}

function getPlaceById(int $id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT lieu_id,adresse,ville,code_postal FROM lieu WHERE lieu_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function getAllAdmin(string $adress = "", int $limit = null, int $offset = null)        //tout les params sont optionnels: le premier pour filtrer par username, le deuxième pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT lieu_id, adresse, ville, code_postal FROM lieu";
    $params = [];

    if (!empty($adress)) {
        $sql .= " WHERE adresse LIKE :adress";
        $params['adress'] = "%" . $adress . "%";
    }

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


function updatePlace(int $id, string $adress = null, string $city = null, string $postalCode = null)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE lieu SET";
    $params = [];
    $coma = "";

    if ($adress !== null) {
        $sql .= $coma . "adresse = :adress";
        $params['adress'] = $adress;
        $coma = ",";
    }
    if ($city !== null) {
        $sql .= $coma . "ville = :city";
        $params['city'] = $city;
        $coma = ",";
    }
    if ($postalCode !== null) {
        $sql .= $coma . "code_postal = :postal_code";
        $params['postal_code'] = $postalCode;
    }

    
    $sql .= " WHERE lieu_id = :id";

    $stmt = $db->prepare($sql);
    $res = $stmt->execute(array_merge($params, ['id' => $id]));
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deletePlace(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM lieu WHERE lieu_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}
