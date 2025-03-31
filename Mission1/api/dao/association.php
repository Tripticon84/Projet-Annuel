<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createAssociation($name, $description)
{
    try{
        $db = getDatabaseConnection();
        $sql = "INSERT INTO association (name, description) VALUES (:name, :description)";
        $stmt = $db->prepare($sql);

        $res=$stmt->execute(['name' => $name, 'description' => $description]);
        if($res){
            return $db->lastInsertId();
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la créartion de l'association: " . $e->getMessage();
        return false;
    }
}

function getAllAssociations(){
    try{
        $db = getDatabaseConnection();
        $sql = "SELECT association_id, name,description FROM association";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Erreur lors de la recuperation des associations : " . $e->getMessage();
        return [];
    }
}


function getAssociationById($association_id){
    try{
        $db = getDatabaseConnection();
        $sql = "SELECT association_id, name, description FROM association WHERE association_id = :association_id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['association_id' => $association_id]);
        if (!$res) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        echo "Erreur lors de la récupération de l'association: " . $e->getMessage();
        return null;
    }
}

function updateAssociation($association_id, ?string $name=null, ?string $description=null){
    
    $db = getDatabaseConnection();
    $params = ['association_id' => $association_id];
    $setFields = [];

    if ($name !== null) {
        $setFields[] = "name = :name";
        $params['name'] = $name;
    }

    if ($description !== null) {
        $setFields[] = "description = :description";
        $params['description'] = $description;
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE association SET " . implode(", ", $setFields) . " WHERE association_id = :association_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteAssociation($association_id){
    $db = getDatabaseConnection();
    $sql = "DELETE FROM association WHERE association_id=:association_id";
    $stmt = $db->prepare($sql);
    // Need to bind the parameter
    $stmt->bindParam(':association_id', $association_id);
    $res = $stmt->execute();
    
    // Check if any rows were affected
    return $stmt->rowCount() > 0;
}

function getAssociationByName($name){
    try{
        $db = getDatabaseConnection();
        $sql = "SELECT name, description FROM association WHERE name = :name";
        $stmt = $db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération de l'association: " . $e->getMessage();
        return null;
    }
}
