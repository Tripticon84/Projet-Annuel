<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/database.php';


function getChat($salon_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('SELECT salon_id, nom, description FROM salon WHERE salon_id = :salon_id');
    $params = [
        'salon_id' => $salon_id
    ];
    $query->execute($params);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getAllChats($limit = null, $offset = null){
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT salon_id, nom, description FROM salon";

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . intval($limit);

        if ($offset !== null) {
            $sql .= " OFFSET " . intval($offset);
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function createChat($nom, $description){
    $db = getDatabaseConnection();
    $query = $db->prepare('INSERT INTO salon (nom, description) VALUES (:nom, :description)');
    $params = [
        'nom' => $nom,
        'description' => $description
    ];
    $query->execute($params);
    return $db->lastInsertId();
}

function updateChat($salon_id, ?string $name = null, ?string $description = null){
    $db = getDatabaseConnection();
    $params = ['salon_id' => $salon_id];
    $setFields = [];

    if ($name !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $name;
    }

    if ($description !== null) {
        $setFields[] = "description = :description";
        $params['description'] = $description;
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE salon SET " . implode(", ", $setFields) . " WHERE salon_id = :salon_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteChat($salon_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('DELETE FROM salon WHERE salon_id = :salon_id');
    $params = [
        'salon_id' => $salon_id
    ];
    $query->execute($params);
    return $query->rowCount();
}

// Fonctions pour la gestion des participants aux salons

/**
 * Ajoute un utilisateur à un salon de discussion
 */
function addUserToChat($salon_id, $collaborateur_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('INSERT INTO discute_dans (id_salon, id_collaborateur) VALUES (:salon_id, :collaborateur_id)');
    $params = [
        'salon_id' => $salon_id,
        'collaborateur_id' => $collaborateur_id
    ];
    $query->execute($params);
    return $query->rowCount();
}

/**
 * Supprime un utilisateur d'un salon de discussion
 */
function removeUserFromChat($salon_id, $collaborateur_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('DELETE FROM discute_dans WHERE id_salon = :salon_id AND id_collaborateur = :collaborateur_id');
    $params = [
        'salon_id' => $salon_id,
        'collaborateur_id' => $collaborateur_id
    ];
    $query->execute($params);
    return $query->rowCount();
}

/**
 * Supprime tous les utilisateurs d'un salon de discussion
 */
function removeAllUsersFromChat($salon_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('DELETE FROM discute_dans WHERE id_salon = :salon_id');
    $params = [
        'salon_id' => $salon_id
    ];
    $query->execute($params);
    return $query->rowCount();
}

/**
 * Récupère les utilisateurs d'un salon de discussion
 */
function getChatUsers($salon_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('
        SELECT c.collaborateur_id, c.nom, c.prenom, c.username, c.email
        FROM collaborateur c
        JOIN discute_dans d ON c.collaborateur_id = d.id_collaborateur
        WHERE d.id_salon = :salon_id
    ');
    $params = [
        'salon_id' => $salon_id
    ];
    $query->execute($params);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les salons de discussion d'un utilisateur
 */
function getUserChats($collaborateur_id){
    $db = getDatabaseConnection();
    $query = $db->prepare('
        SELECT s.salon_id, s.nom, s.description
        FROM salon s
        JOIN discute_dans d ON s.salon_id = d.id_salon
        WHERE d.id_collaborateur = :collaborateur_id
    ');
    $params = [
        'collaborateur_id' => $collaborateur_id
    ];
    $query->execute($params);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonctions pour la gestion des messages en JSON
function saveMessage($salon_id, $collaborateur_id, $message, $timestamp = null){
    if ($timestamp === null) {
        $timestamp = time();
    }

    $messagesFile = $_SERVER['DOCUMENT_ROOT'] . "/data/messages/salon_" . $salon_id . ".json";
    $directory = dirname($messagesFile);

    // Créer le répertoire s'il n'existe pas
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    // Charger les messages existants ou créer un tableau vide
    $messages = [];
    if (file_exists($messagesFile)) {
        $jsonContent = file_get_contents($messagesFile);
        $messages = json_decode($jsonContent, true) ?: [];
    }

    // Ajouter le nouveau message
    $messages[] = [
        'collaborateur_id' => $collaborateur_id,
        'message' => $message,
        'timestamp' => $timestamp
    ];

    // Sauvegarder le fichier JSON
    return file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
}

function getMessages($salon_id, $limit = null, $offset = 0){
    $messagesFile = $_SERVER['DOCUMENT_ROOT'] . "/data/messages/salon_" . $salon_id . ".json";

    if (!file_exists($messagesFile)) {
        return [];
    }

    $jsonContent = file_get_contents($messagesFile);
    $messages = json_decode($jsonContent, true) ?: [];

    // Trier les messages par timestamp (plus récents en dernier)
    usort($messages, function($a, $b) {
        return $a['timestamp'] - $b['timestamp'];
    });

    // Appliquer limit et offset
    if ($limit !== null) {
        return array_slice($messages, $offset, $limit);
    }

    return array_slice($messages, $offset);
}

function getLatestMessages($salon_id, $count = 20){
    $messagesFile = $_SERVER['DOCUMENT_ROOT'] . "/data/messages/salon_" . $salon_id . ".json";

    if (!file_exists($messagesFile)) {
        return [];
    }

    $jsonContent = file_get_contents($messagesFile);
    $messages = json_decode($jsonContent, true) ?: [];

    // Trier les messages par timestamp (les plus récents en premier)
    usort($messages, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    // Retourner directement les $count messages les plus récents
    return array_slice($messages, 0, $count);
}
