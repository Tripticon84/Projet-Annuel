<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";


function createSociety($nom,$email,$adresse,$contact_person,$password,$telephone) {
    $db = getDatabaseConnection();

    // Hasher le mot de passe
    $password = hashPassword($password);

    $sql = "INSERT INTO societe (nom, email, adresse, contact_person, password, date_creation) VALUES (:nom, :email, :adresse, :contact_person, :password, :telephone, :date_creation)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'adresse' => $adresse,
        'contact_person' => $contact_person,
        'password' => $password,
        'telephone' => $telephone,
        'date_creation' => date('Y-m-d H:i:s')
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}


function getSocietyByEmail($email)
{
    $db = getDatabaseConnection();
    $sql = "SELECT id,email FROM societe WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSocietyByTelephone($telephone)
{
    $db = getDatabaseConnection();
    $sql = "SELECT id,telephone FROM societe WHERE telephone = :telephone";
    $stmt = $db->prepare($sql);
    $stmt->execute(['telephone' => $telephone]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSocietyById($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT id, nom, email, adresse, contact_person, telephone, date_creation FROM societe WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteSociety($id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM societe WHERE id = :id";
    $stmt = $db->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

function updateSociety($id, ?string $nom = null, ?string $email = null, ?string $adresse = null, ?string $contact_person = null, ?string $password = null, ?int $telephone = null)
{
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($nom !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }

    if ($email !== null) {
        $setFields[] = "email = :email";
        $params['email'] = $email;
    }

    if ($adresse !== null) {
        $setFields[] = "adresse = :adresse";
        $params['adresse'] = $adresse;
    }

    if ($contact_person !== null) {
        $setFields[] = "contact_person = :contact_person";
        $params['contact_person'] = $contact_person;
    }

    if ($telephone !== null) {
        $setFields[] = "telephone = :telephone";
        $params['telephone'] = $telephone;
    }

    if ($password !== null) {
        $setFields[] = "password = :password";
        $params['password'] = hashPassword($password);
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE societe SET " . implode(", ", $setFields) . " WHERE societe_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;

}


function getAllSociety($name = '', $limit = null, $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT id, nom, email, adresse, contact_person, telephone, date_creation FROM societe WHERE nom LIKE :name LIMIT :limit OFFSET :offset";
    if (!empty($name)) {
        $sql .= " WHERE nom LIKE :nom";
        $params['name'] = "%" . $name . "%";
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
    return null;;
}

function getSociety($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT id, nom, email, adresse, contact_person, telephone, date_creation FROM societe WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}