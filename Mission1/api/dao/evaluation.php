<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createEvaluation(int $note, String $commentaire, int $id_collaborateur,$date_creation){
    $db = getDatabaseConnection();
    $sql = "INSERT INTO evaluation (note, commentaire, id_collaborateur,date_creation) VALUES (:note, :commentaire, :id_collaborateur, :date_creation)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'note' => $note,
        'commentaire' => $commentaire,
        'id_collaborateur' => $id_collaborateur,
        'date_creation' => $date_creation
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getEvaluationByCollaboratorId(int $id){
    $db = getDatabaseConnection();
    $sql = "SELECT note, commentaire, id_collaborateur, date_creation FROM evaluation WHERE id_collaborateur = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEvaluation($id){
    $db = getDatabaseConnection();
    $sql = "SELECT evaluation_id, note, commentaire, id_collaborateur, date_creation FROM evaluation WHERE evaluation_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}


function getAllEvaluation(?int $note,?int $limit, ?int $offset){
    $db = getDatabaseConnection();
    $params = [];

    $sql = "SELECT evaluation_id, note, commentaire, id_collaborateur, date_creation FROM evaluation";

    if ($note !== null) {
        $sql .= " WHERE note = :note";
        $params['note'] = $note;
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

function deleteEvaluationInEvaluation(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM evaluation WHERE evaluation_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }
    return null; // En cas d'erreur, retourne null
}


function deleteInNote_prestataire(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM note_prestataire WHERE id_evaluation = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id
    ]);
    if ($res) {
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }
    return null; // En cas d'erreur, retourne null
}

function deleteEvaluation(int $id)
{
     // Suppression de l'évaluation dans la table note_prestataire
    $deletedNote = deleteInNote_prestataire($id);
    if ($deletedNote === null) {
        return false; // Échec de la suppression dans note_prestataire
    }
    echo json_encode($deletedNote);
    // Vérification si la suppression a réussi  
    // Suppression de l'évaluation dans la table evaluation
    $deletedEval = deleteEvaluationInEvaluation($id);
    if ($deletedEval === null) {
        return false; // Échec de la suppression dans evaluation
    }

    if ($deletedEval && $deletedNote) {
        return true; // Les deux suppressions ont réussi
    }
    return false; // Une ou les deux suppressions ont échoué
}


function updateEvent(?int $id, ?int $note = null, ?String $commentaire = null , ?int $id_collaborateur = null, $date_creation = null){
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($note !== null) {
        $setFields[] = "note = :note";
        $params['note'] = $note;
    }

    if ($commentaire !== null) {
        $setFields[] = "commentaire = :commentaire";
        $params['commentaire'] = $commentaire;
    }

    if ($id_collaborateur !== null) {
        $setFields[] = "id_collaborateur = :id_collaborateur";
        $params['id_collaborateur'] = $id_collaborateur;
    }

    if ($date_creation !== null) {
        $setFields[] = "date_creation = :date_creation";
        $params['date_creation'] = $date_creation;
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


function newEvaluationInNote_prestataire($id_evaluation,$id_prestataire){
    $db = getDatabaseConnection();
    $sql = "INSERT INTO note_prestataire (id_evaluation, id_prestataire) VALUES (:id_evaluation, :id_prestataire)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id_evaluation' => $id_evaluation,
        'id_prestataire' => $id_prestataire
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}