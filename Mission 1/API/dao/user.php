<?php

function createUser(string $email, int $password) {
    $db = getDatabaseConnection();
    $sql = "INSERT INTO viking (email, password) VALUES (:email, :password)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['email' => $email,'password' => $password]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}


