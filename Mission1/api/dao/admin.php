<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createAdmin(string $email, string $password){
    $password = hashPassword($password);
    $db = getDatabaseConnection();
    $sql = "INSERT INTO admin (email, password) VALUES (:email, :password)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['email' => $email, 'password' => $password]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateAdmin(int $id, string $email, string $password = null) {              //password est definit a null par defaut si password n est pas preciser
    $db = getDatabaseConnection();

    // Si seul l'email est fourni (pas de mot de passe)
    if ($password === null) {
        $sql = "UPDATE admin SET email = :email WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['id' => $id, 'email' => $email]);
    }
    // Si email et mot de passe sont fournis
    else {
        $password = hashPassword($password);
        $sql = "UPDATE admin SET email = :email, password = :password WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['id' => $id, 'email' => $email, 'password' => $password]);
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
    $sql = "GET email FROM admin WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}

function getAllAdmin(string $email = "", int $limit = null, int $offset = null)        //tout les params sont optionnels le premier est pour filter par email, le deuxieme est pour definier la limite de resultat et le last est pour definir ou on commence->utile pour la pagination
{
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT id, email FROM admin";
    if ($email) {
        $sql .= " WHERE email LIKE :email";
        $params['email'] = "%".$email."%";
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
    $sql = "SELECT admin_id FROM admin WHERE email = :email AND password = :password";
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
    $sql = "SELECT admin_id, email, password FROM admin WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute(['token' => $token]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
