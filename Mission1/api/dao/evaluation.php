<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createEvaluation(int $note, String $comment, int $collaborateurId){
    $db = getDatabaseConnection();
    $sql = "INSERT INTO evaluation (note, comment, collaborateur_id,date) VALUES (:note, :comment, :collaborateurId, :date)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'note' => $note,
        'comment' => $comment,
        'collaborateurId' => $collaborateurId,
        'date' => date('Y-m-d H:i:s')
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getEvaluationByCollaboratorId(int $id){
    $db = getDatabaseConnection();
    $sql = "SELECT note, comment,collaborateur_id, date FROM evaluation WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEvalution(){
    $db = getDatabaseConnection();
    $sql = "SELECT evaluation_id, note, comment,collaborateur_id,date FROM evaluation";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}


function deleteEvent(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM evaluation WHERE evaluation_id = :id AND DELETE FROM note_prestataire WHERE evaluation_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "id" => $id
    ]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

//les champs sont optionnels
function updateEvent(?int $id, ?int $note, ?String $comment, ?int $collaborateurId, $date){
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($note !== null) {
        $setFields[] = "note = :note";
        $params['note'] = $note;
    }

    if ($comment !== null) {
        $setFields[] = "comment = :comment";
        $params['comment'] = $comment;
    }

    if ($collaborateurId !== null) {
        $setFields[] = "collaborateur_id = :collaborateurId";
        $params['collaborateurId'] = $collaborateurId;
    }

    if ($date !== null) {
        $setFields[] = "date = :date";
        $params['date'] = $date;
    }

    if (empty($setFields)) {
        return false; // Aucun champ à mettre à jour
    }

    $sql = "UPDATE evaluation SET " . implode(", ", $setFields) . " WHERE evaluation_id = :id";
    $stmt = $db->prepare($sql);
    return $stmt->execute($params);
}


function getProviderByEvaluation (int $id){
    $db = getDatabaseConnection();
    $sql = "SELECT p.prestataire_id, p.nom, p.type, p.tarif, p.date_debut_disponibilite, p.date_fin_disponibilite 
    FROM prestataire p
    JOIN note_prestataire n ON p.prestataire_id = n.prestataire_id
    JOIN evalutation e ON e.evaluation_id = n.evaluation_id
    WHERE e.evaluation_id = :id";    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}