<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createProvider(string $username, string $password)
{
    $password = hashPassword($password);
    $db = getDatabaseConnection();
    $sql = "INSERT INTO admin (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'username' => $username,
        'password' => $password
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateProvider(int $id, string $username, string $password = null)
{              //password est definit a null par defaut si password n est pas preciser
    $db = getDatabaseConnection();

    // Si seul l'username est fourni (pas de mot de passe)
    if ($password === null) {
        $sql = "UPDATE admin SET username = :username WHERE admin_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute([
            'id' => $id,
            'username' => $username
        ]);
    }
    // Si username et mot de passe sont fournis
    else {
        $password = hashPassword($password);
        $sql = "UPDATE admin SET username = :username, password = :password WHERE admin_id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute([
            'id' => $id,
            'username' => $username,
            'password' => $password
        ]);
    }

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteProvider(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM admin WHERE admin_id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "id" => $id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}


function getProviderByUsername(string $username)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, username FROM prestataire WHERE username = :username";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'username' => $username
    ]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProviderById($id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT prestataire_id, username, password FROM admin WHERE admin_id = :id";
    $query = $connection->prepare($sql);
    $res = $query->execute(['id' => $id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}


function getAllProvider(int $limit = null, int $offset = null)        //tout les params sont optionnels:  le premier pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email, nom, prenom, type, tarif, date_debut_disponibilite, date_fin_disponibilite FROM prestataire Where est_candidat = false";
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

function getAllCandidate(int $limit = null, int $offset = null)        //tout les params sont optionnels:  le premier pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email, nom, prenom, type, tarif, date_debut_disponibilite, date_fin_disponibilite FROM prestataire Where est_candidat = true";
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
