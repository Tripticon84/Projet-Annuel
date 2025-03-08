<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createAdmin(string $username, string $password){
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

function updateAdmin(int $id, string $username, string $password = null) {              //password est definit a null par defaut si password n est pas preciser
    $db = getDatabaseConnection();

    // Si seul l'username est fourni (pas de mot de passe)
    if ($password === null) {
        $sql = "UPDATE admin SET username = :username WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['id' => $id, 'username' => $username]);
    }
    // Si username et mot de passe sont fournis
    else {
        $password = hashPassword($password);
        $sql = "UPDATE admin SET username = :username, password = :password WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['id' => $id, 'username' => $username, 'password' => $password]);
    }

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteAdmin(int $id){
    $db=getDatabaseConnection();
    $sql = "DELETE FROM admin WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}


function getAdmin(int $id)
{
    $db = getDatabaseConnection();
    $sql = "GET username FROM username WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}

function getAllAdmin(string $username = "", int $limit = null, int $offset = null): array|null        //tout les params sont optionnels le premier est pour filter par username, le deuxieme est pour definier la limite de resultat et le last est pour definir ou on commence->utile pour la pagination
{
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT admin_id, username FROM admin";
    if ($username) {
        $sql .= " WHERE username LIKE :username";
        $params['username'] = "%".$username."%";
    }
    if ($limit !== null) {
        $sql .= " LIMIT $limit";

        if ($offset !== null) {
            $sql .= " OFFSET $offset";
        }
    }
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}


/* Authentification */

function findAdminByCredentials($name, $password) {
    $connection = getDatabaseConnection();
    $sql = "SELECT admin_id FROM admin WHERE username = :username AND password = :password";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'name' => $name,
        'password' => $password
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function setAdminSession ($id, $token) {
    $connection = getDatabaseConnection();
    $sql = "UPDATE admin SET token = :token, expiration = DATE_ADD(NOW(), INTERVAL 5 HOUR) WHERE id = :id";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'id' => $id,
        'token' => $token
    ]);
    if ($res) {
        return $query->rowCount();
    }
    return null;
}


function getTokenByExpiration($token) {
    $connection = getDatabaseConnection();
    $sql = "SELECT expiration FROM admin WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'token' => $token
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

function getAdminByToken($token) {
    $connection = getDatabaseConnection();
    $sql = "SELECT admin_id, username, password FROM admin WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute(['token' => $token]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
