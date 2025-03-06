<?php

function createUser(string $email, int $password)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO viking (email, password) VALUES (:email, :password)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['email' => $email, 'password' => $password]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getUser(int $id)
{
    $db = getDatabaseConnection();
    $sql = "GET email, password FROM users WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}

function getAllUser(string $email = "", int $limit = 10, int $offset = 0)
{
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT id, email FROM users";
    if ($email) {
        $sql .= " WHERE email LIKE %:email%";
        $params['email'] = $email;
    }
    $sql .= " LIMIT $limit OFFSET $offset ";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function deleteUser(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM users WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}

function updateUser(int $id, string $email, string $password)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id, 'email' => $email, 'password' => $password]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}
