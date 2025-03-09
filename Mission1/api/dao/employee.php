<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";

function createEmployee(
    string $nom,
    string $prenom,
    string $username,
    string $role = null,
    string $email = null,
    string $password = null,
    string $telephone = null,
    int $id_societe = null
) {
    $db = getDatabaseConnection();

    // Hasher le mot de passe si fourni
    if ($password !== null) {
        $password = hashPassword($password);
    }

    $sql = "INSERT INTO collaborateur (nom, prenom, username, role, email, password, telephone, id_societe) VALUES (:nom, :prenom, :username, :role, :email, :password, :telephone, :id_societe)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'username' => $username,
        'role' => $role,
        'email' => $email,
        'password' => $password,
        'telephone' => $telephone,
        'id_societe' => $id_societe
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getEmployee(int $id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, password, telephone, id_societe FROM collaborateur WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllEmployees(string $username = "", int $limit = null, int $offset = null, int $id_societe = null)
{
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe FROM collaborateur";

    if (!empty($username)) {
        $sql .= " WHERE username LIKE :username";
        $params['username'] = "%" . $username . "%";
    }

    if (!is_null($id_societe)) {
        $conditions[] = "id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
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

function deleteEmployee(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM collaborateur WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function updateEmployee(int $id, ?string $nom = null, ?string $prenom = null, ?string $role = null, ?string $email = null, ?string $telephone = null, ?int $id_societe = null, ?string $username = null, ?string $password = null)
{
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($nom !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }

    if ($prenom !== null) {
        $setFields[] = "prenom = :prenom";
        $params['prenom'] = $prenom;
    }

    if ($role !== null) {
        $setFields[] = "role = :role";
        $params['role'] = $role;
    }

    if ($email !== null) {
        $setFields[] = "email = :email";
        $params['email'] = $email;
    }

    if ($telephone !== null) {
        $setFields[] = "telephone = :telephone";
        $params['telephone'] = $telephone;
    }

    if ($id_societe !== null) {
        $setFields[] = "id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
    }

    if ($username !== null) {
        $setFields[] = "username = :username";
        $params['username'] = $username;
    }

    if ($password !== null) {
        $setFields[] = "password = :password";
        $params['password'] = hashPassword($password);
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE collaborateur SET " . implode(", ", $setFields) . " WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getEmployeeByTelephone(string $telephone)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, role, email, telephone, id_societe, username FROM collaborateur WHERE telephone = :telephone";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['telephone' => $telephone]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByEmail(string $email)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, role, email, telephone, id_societe, username FROM collaborateur WHERE email = :email";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['email' => $email]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeesBySociete(int $id_societe)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, role, email, telephone, id_societe, username FROM collaborateur WHERE id_societe = :id_societe";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id_societe' => $id_societe]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

/* Authentification */

function findEmployeeByCredentials($username, $password)
{
    $connection = getDatabaseConnection();
    $hashedPassword = hashPassword($password);
    $sql = "SELECT collaborateur_id FROM collaborateur WHERE username = :username AND password = :password";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'username' => $username,
        'password' => $hashedPassword
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function setEmployeeSession($id, $token)
{
    $connection = getDatabaseConnection();
    $sql = "UPDATE collaborateur SET token = :token, expiration = DATE_ADD(NOW(), INTERVAL 5 HOUR) WHERE collaborateur_id = :id";
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

function getEmployeeTokenByExpiration($token)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT expiration FROM collaborateur WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'token' => $token
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByToken($token)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username FROM collaborateur WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute(['token' => $token]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByUsername($username)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom FROM collaborateur WHERE username = :username";
    $query = $connection->prepare($sql);
    $res = $query->execute(['username' => $username]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
