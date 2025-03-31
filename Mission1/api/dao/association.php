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

function getEmployeesByAssociation($association_id, $limit = null, $offset = null){
    try{
        $db = getDatabaseConnection();
        $sql = "SELECT c.collaborateur_id, c.nom, c.prenom, c.username, c.email, c.role, c.telephone, c.id_societe, c.date_creation
                FROM collaborateur c
                JOIN participe_association pa ON c.collaborateur_id = pa.id_collaborateur
                WHERE pa.id_association = :association_id AND c.desactivate = 0";

        // Ajouter la pagination si les paramètres sont fournis
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':association_id', $association_id, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Erreur lors de la récupération des employés de l'association: " . $e->getMessage();
        return [];
    }
}
