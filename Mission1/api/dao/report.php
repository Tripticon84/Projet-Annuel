<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


function createReport($description, $probleme, $id_societe){
    $db = getDatabaseConnection();
    $sql = "INSERT INTO signalement (description, probleme, id_societe) VALUES (:description, :probleme, :id_societe)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'description' => $description,
        'probleme' => $probleme,
        'id_societe' => $id_societe
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}
