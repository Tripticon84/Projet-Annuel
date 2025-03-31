<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/database.php';

function createEvent($nom, $date, $lieu, $type, $statut, $id_association) {
    $db = getDatabaseConnection();
    

    $checkAssoc = $db->prepare('SELECT association_id FROM association WHERE association_id = :id_association');
    $checkAssoc->execute(['id_association' => $id_association]);
    if (!$checkAssoc->fetch()) {
        return false; 
    }
    
    $query = $db->prepare('INSERT INTO evenements (nom, date, lieu, type, statut, id_association) VALUES (:nom, :date, :lieu, :type, :statut, :id_association)');
    $res = $query->execute([
        'nom' => $nom,
        'date' => $date,
        'lieu' => $lieu,
        'type' => $type,
        'statut' => $statut,
        'id_association' => $id_association
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getEvent($event_id) {
    $db = getDatabaseConnection();
    $query = $db->prepare('SELECT e.evenement_id, e.nom, e.date, e.lieu, e.type, e.statut, e.id_association, a.name as association_name 
                          FROM evenements e 
                          JOIN association a ON e.id_association = a.association_id 
                          WHERE e.evenement_id = :event_id');
    $params = [
        'event_id' => $event_id
    ];
    $query->execute($params);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getAllEvents($limit = null, $offset = null) {
    $db = getDatabaseConnection();
    $sql = "SELECT e.evenement_id, e.nom, e.date, e.lieu, e.type, e.statut, e.id_association, a.name as association_name 
            FROM evenements e 
            JOIN association a ON e.id_association = a.association_id";
    $params = [];

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

function updateEvent($event_id, string $nom = null, string $date = null, string $lieu = null, string $type = null, string $statut = null, int $id_association = null) {
    $db = getDatabaseConnection();
    
    // If id_association is provided, verify it exists
    if ($id_association !== null) {
        $checkAssoc = $db->prepare('SELECT association_id FROM association WHERE association_id = :id_association');
        $checkAssoc->execute(['id_association' => $id_association]);
        if (!$checkAssoc->fetch()) {
            return false; // Association doesn't exist
        }
    }
    
    $params = ['event_id' => $event_id];
    $setFields = [];

    if ($nom !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }
    if ($date !== null) {
        $setFields[] = "date = :date";
        $params['date'] = $date;
    }
    if ($lieu !== null) {
        $setFields[] = "lieu = :lieu";
        $params['lieu'] = $lieu;
    }
    if ($type !== null) {
        $setFields[] = "type = :type";
        $params['type'] = $type;
    }
    if ($statut !== null) {
        $setFields[] = "statut = :statut";
        $params['statut'] = $statut;
    }
    if ($id_association !== null) {
        $setFields[] = "id_association = :id_association";
        $params['id_association'] = $id_association;
    }

    if (empty($setFields)) {
        return 0;
    }

    $sql = "UPDATE evenements SET " . implode(", ", $setFields) . " WHERE evenement_id = :event_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteEvent($evenement_id) {
    $db = getDatabaseConnection();
    
    $db->beginTransaction();
    
    try {
        $query = $db->prepare('DELETE FROM participe_evenement WHERE id_evenement = :evenement_id');
        $query->execute(['evenement_id' => $evenement_id]);
        
        $query = $db->prepare('DELETE FROM evenements WHERE evenement_id = :evenement_id');
        $query->execute(['evenement_id' => $evenement_id]);
        
        $db->commit();
        return true;
        
    } catch(Exception $e) {
        $db->rollBack();
        return false;
    }
}

function getEventById($event_id) {
    $db = getDatabaseConnection();
    $query = $db->prepare('SELECT e.evenement_id, e.nom, e.date, e.lieu, e.type, e.statut, e.id_association, a.name as association_name 
                          FROM evenements e 
                          JOIN association a ON e.id_association = a.association_id 
                          WHERE e.evenement_id = :event_id');
    $params = [
        'event_id' => $event_id
    ];
    $query->execute($params);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getEventByStatut($statut) {
    $db = getDatabaseConnection();
    $query = $db->prepare('SELECT e.evenement_id, e.nom, e.date, e.lieu, e.type, e.statut, e.id_association, a.name as association_name 
                          FROM evenements e 
                          JOIN association a ON e.id_association = a.association_id 
                          WHERE e.statut = :statut');
    $params = [
        'statut' => $statut
    ];
    $query->execute($params);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}