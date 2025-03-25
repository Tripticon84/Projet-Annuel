<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createAssociation($name, $description)
{
    try{
        $db = getDatabaseConnection();
        $sql = "INSERT INTO association (name, desciption) VALUES (:name, :description)";
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
        $sql = "SELECT id, name,description FROM association";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Erreur lors de la recuperation des associations : " . $e->getMessage();
        return [];
    }
}


function getAssociationById($id){
    try{
        $db = getDatabaseConnection();
        $sql = "SELECT id, name, description FROM association WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute();
        if (!$res) {
            return 404;
        }
    }catch (PDOException $e){
        echo "Erreur lors de la récupération de l'association: " . $e->getMessage();
        return null;
    }
}

function updateAssociation($id, $name, $description){
    try{
        $db = getDatabaseConnection();
        $sql = "UPDATE association SET name = :name, description = :description WHERE id = :id";
        $stmt = $db->prepare($sql);

        $res=$stmt->execute(['name' => $name, 'description' => $description]);
        if ($res) {
            return $stmt->rowCount();
        }
    }catch(PDOException $e){
        echo "Erreur lors de la mise a jour de l'association: " . $e->getMessage();
        return false;
    }
}

function deleteAssociation($id){
    $db = getDatabaseConnection();
    $sql = "DELETE FROM association WHERE id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute();
    if (!$res) {
        return 404;
    }
}
