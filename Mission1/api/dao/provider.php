<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";


function createProvider($email, $nom, $prenom, $type, $description, $tarif, $date_debut_disponibilite, $date_fin_disponibilite, $est_candidat, $password)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO prestataire (email, nom, prenom, type, description, tarif, date_debut_disponibilite, date_fin_disponibilite, est_candidat, password) VALUES (:email, :nom, :prenom, :type, :description, :tarif, :date_debut_disponibilite, :date_fin_disponibilite, :est_candidat, :password)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'email' => $email,
        'nom' => $nom,
        'prenom' => $prenom,
        'type' => $type,
        'description' => $description,
        'tarif' => $tarif,
        'date_debut_disponibilite' => $date_debut_disponibilite,
        'date_fin_disponibilite' => $date_fin_disponibilite,
        'est_candidat' => $est_candidat,
        'password' => hashPassword($password)
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateProvider($prestataire_id, $firstname, $name, $type, $est_candidat, $tarif, $email, $date_debut_disponibilite, $date_fin_disponibilite, $password = null, $description = null)
{
    $db = getDatabaseConnection();
    $params = ['prestataire_id' => $prestataire_id];
    $setFields = [];

    if ($firstname !== null) {
        $setFields[] = "prenom = :prenom";
        $params['prenom'] = $firstname;
    }

    if ($name !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $name;
    }

    if ($type !== null) {
        $setFields[] = "type = :type";
        $params['type'] = $type;
    }

    if ($est_candidat !== null) {
        $setFields[] = "est_candidat = :est_candidat";
        $params['est_candidat'] = $est_candidat;
    }

    if ($tarif !== null) {
        $setFields[] = "tarif = :tarif";
        $params['tarif'] = $tarif;
    }

    if ($email !== null) {
        $setFields[] = "email = :email";
        $params['email'] = $email;
    }

    if ($date_debut_disponibilite !== null) {
        $setFields[] = "date_debut_disponibilite = :date_debut_disponibilite";
        $params['date_debut_disponibilite'] = $date_debut_disponibilite;
    }

    if ($date_fin_disponibilite !== null) {
        $setFields[] = "date_fin_disponibilite = :date_fin_disponibilite";
        $params['date_fin_disponibilite'] = $date_fin_disponibilite;
    }

    if ($description !== null) {
        $setFields[] = "description = :description";
        $params['description'] = $description;
    }

    if ($password !== null) {
        $setFields[] = "password = :password";
        $params['password'] = hashPassword($password);
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE prestataire SET " . implode(", ", $setFields) . " WHERE prestataire_id = :prestataire_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

              //password est definit a null par defaut si password n est pas preciser


function deleteProvider(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM prestataire WHERE prestataire_id=:id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "id" => $id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}


function getProviderByUsername(string $username)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, username FROM prestataire WHERE username = :username";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'username' => $username
    ]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProviderById($id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email, nom, prenom, description, tarif,  date_debut_disponibilite, date_fin_disponibilite, est_candidat FROM prestataire WHERE prestataire_id = :id";
    $query = $connection->prepare($sql);
    $res = $query->execute(['id' => $id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProviderByEmail($email){
    $connection = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email  FROM prestataire WHERE email = :email";
    $query = $connection->prepare($sql);
    $res = $query->execute(['email' => $email]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllProvider(int $limit = null, int $offset = null, string $search = null)        //tout les params sont optionnels:  le premier pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email, nom, prenom, type, tarif, date_debut_disponibilite, date_fin_disponibilite FROM prestataire Where est_candidat = false AND desactivate = 0";
    $params = [];

    // Ajout du filtre de recherche
    if ($search !== null && $search !== '') {
        $sql .= " AND (LOWER(nom) LIKE LOWER(:search) OR LOWER(prenom) LIKE LOWER(:search) OR LOWER(email) LIKE LOWER(:search))";
        $params['search'] = "%$search%";
    }

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);  // Seuls les paramètres username seront utilisés

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllCandidate(int $limit = null, int $offset = null, string $search = null)        // tout les params sont optionnels:  le premier pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
{
    $db = getDatabaseConnection();
    $sql = "SELECT prestataire_id, email, nom, prenom, type, tarif, date_debut_disponibilite, date_fin_disponibilite FROM prestataire WHERE est_candidat = true AND desactivate = 0";
    $params = [];

    // Ajout du filtre de recherche
    if ($search !== null && $search !== '') {
        $sql .= " AND (LOWER(nom) LIKE LOWER(:search) OR LOWER(prenom) LIKE LOWER(:search) OR LOWER(email) LIKE LOWER(:search))";
        $params['search'] = "%$search%";
    }

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);  // Seuls les paramètres username seront utilisés

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}


function updateCandidateStatus($prestataire_id,$value){
    $db = getDatabaseConnection();
    $sql = "UPDATE prestataire  SET est_candidat = :est_candidat WHERE prestataire_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'est_candidat'=>$value,
        'id'=>$prestataire_id
    ]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}


function getAllActivities(int $limit = null, int $offset = null, $providerId)  {
    $db = getDatabaseConnection();
    $sql = "SELECT activite_id, nom, date, id_lieu, type, id_devis FROM activite WHERE id_prestataire = :id";
    $params = [
        'id'=> $providerId
    ];

    // Gestion des paramètres LIMIT et OFFSET
    if ($limit !== null) {
        $sql .= " LIMIT " . (string) $limit;

        if ($offset !== null) {
            $sql .= " OFFSET " . (string) $offset;
        }
    }

    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);  // Seuls les paramètres username seront utilisés

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

/* Authentification */

function findProviderByCredentials($email, $password)
{
    $connection = getDatabaseConnection();
    $hashedPassword = hashPassword($password);
    $sql = "SELECT prestataire_id FROM prestataire WHERE email = :email AND password = :password";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'email' => $email,
        'password' => $hashedPassword
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function setProviderSession($id, $token) {
    $connection = getDatabaseConnection();
    // Ajouter les colonnes token et expiration à la table prestataire si elles n'existent pas
    $sql = "UPDATE prestataire SET token = :token, expiration = DATE_ADD(NOW(), INTERVAL 2 HOUR) WHERE prestataire_id = :id";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'id' => $id,
        'token' => $token
    ]);
    if ($res) {
        return $query->rowCount();
    }
    return null;
}

function getProviderExpirationByToken($token) {
    $connection = getDatabaseConnection();
    $sql = "SELECT expiration FROM prestataire WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'token' => $token
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProviderByToken($token)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT prestataire_id, nom, prenom, email, type FROM prestataire WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute(['token' => $token]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function deactivateProvider($prestataire_id) {
    $db = getDatabaseConnection();
    $sql = "UPDATE prestataire SET desactivate = 1 WHERE prestataire_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $prestataire_id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function activateProvider($prestataire_id) {
    $db = getDatabaseConnection();
    $sql = "UPDATE prestataire SET desactivate = 0 WHERE prestataire_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $prestataire_id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}
