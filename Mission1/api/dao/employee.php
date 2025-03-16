<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/hashPassword.php";

function createEmployee(
    string $nom,
    string $prenom,
    string $username,
    string $role = null,
    string $email = null,
    string $password = null,
    string $telephone = null,
    int $id_societe = null
) {
    $db = getDatabaseConnection();

    // Hasher le mot de passe si fourni
    if ($password !== null) {
        $password = hashPassword($password);
    }

    $sql = "INSERT INTO collaborateur (nom, prenom, username, role, email, password, telephone, id_societe, date_creation) VALUES (:nom, :prenom, :username, :role, :email, :password, :telephone, :id_societe, :date_creation)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'username' => $username,
        'role' => $role,
        'email' => $email,
        'password' => $password,
        'telephone' => $telephone,
        'id_societe' => $id_societe,
        'date_creation' => date('Y-m-d H:i:s')
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function getEmployee(int $id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe, date_creation, date_activite FROM collaborateur WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllEmployees(string $username = "", int $limit = null, int $offset = null, int $id_societe = null)
{
    $db = getDatabaseConnection();
    $params = [];
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe, date_creation, date_activite FROM collaborateur";

    if (!empty($username)) {
        $sql .= " WHERE username LIKE :username";
        $params['username'] = "%" . $username . "%";
    }

    if (!is_null($id_societe)) {
        $conditions[] = "id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
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

// function deleteEmployee(int $id)
// {
//     $db = getDatabaseConnection();
//     $sql = "DELETE FROM collaborateur WHERE collaborateur_id = :id";
//     $stmt = $db->prepare($sql);
//     $res = $stmt->execute(['id' => $id]);
//     if ($res) {
//         return $stmt->rowCount();
//     }
//     return null;
// }

function deleteEmployee(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM collaborateur WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    return $res;
}


function updateEmployee(int $id, ?string $nom = null, ?string $prenom = null, ?string $role = null, ?string $email = null, ?string $telephone = null, ?int $id_societe = null, ?string $username = null, ?string $password = null)
{
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($nom !== null) {
        $setFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }

    if ($prenom !== null) {
        $setFields[] = "prenom = :prenom";
        $params['prenom'] = $prenom;
    }

    if ($role !== null) {
        $setFields[] = "role = :role";
        $params['role'] = $role;
    }

    if ($email !== null) {
        $setFields[] = "email = :email";
        $params['email'] = $email;
    }

    if ($telephone !== null) {
        $setFields[] = "telephone = :telephone";
        $params['telephone'] = $telephone;
    }

    if ($id_societe !== null) {
        $setFields[] = "id_societe = :id_societe";
        $params['id_societe'] = $id_societe;
    }

    if ($username !== null) {
        $setFields[] = "username = :username";
        $params['username'] = $username;
    }

    if ($password !== null) {
        $setFields[] = "password = :password";
        $params['password'] = hashPassword($password);
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE collaborateur SET " . implode(", ", $setFields) . " WHERE collaborateur_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getEmployeeByTelephone(string $telephone)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe, date_creation, date_activite FROM collaborateur WHERE telephone = :telephone";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['telephone' => $telephone]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByEmail(string $email)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe, date_creation, date_activite FROM collaborateur WHERE email = :email";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['email' => $email]);
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeesBySociete(int $id_societe)
{
    $db = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username, role, email, telephone, id_societe, date_creation, date_activite FROM collaborateur WHERE id_societe = :id_societe";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id_societe' => $id_societe]);
    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

/* Authentification */

function findEmployeeByCredentials($username, $password)
{
    $connection = getDatabaseConnection();
    $hashedPassword = hashPassword($password);
    $sql = "SELECT collaborateur_id FROM collaborateur WHERE username = :username AND password = :password";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'username' => $username,
        'password' => $hashedPassword
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function setEmployeeSession($id, $token)
{
    $connection = getDatabaseConnection();
    $sql = "UPDATE collaborateur SET token = :token, expiration = DATE_ADD(NOW(), INTERVAL 5 HOUR) WHERE collaborateur_id = :id";
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

function getEmployeeTokenByExpiration($token)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT expiration FROM collaborateur WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute([
        'token' => $token
    ]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByToken($token)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom, username FROM collaborateur WHERE token = :token";
    $query = $connection->prepare($sql);
    $res = $query->execute(['token' => $token]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function getEmployeeByUsername($username)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT collaborateur_id, nom, prenom FROM collaborateur WHERE username = :username";
    $query = $connection->prepare($sql);
    $res = $query->execute(['username' => $username]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}


// Récupération des statistiques des employés
function getEmployeeStats()
{
    $db = getDatabaseConnection();
    $stats = [
        'total' => 0,
        'totalLastMonth' => 0,
        'active' => 0,
        'activeLastMonth' => 0,
        'new' => 0,
        'newLastMonth' => 0,
        'participationRate' => 0,
        'participationRateLastMonth' => 0
    ];

    // Date du premier jour du mois courant
    $currentMonthStart = date('Y-m-01');
    // Date du premier jour du mois précédent
    $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
    // Date du premier jour du mois suivant (pour limiter le mois courant)
    $nextMonthStart = date('Y-m-01', strtotime('+1 month'));

    try {
        // Nombre total d'employés inscrits
        $query = "SELECT COUNT(*) as total FROM collaborateur";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total'] = $result['total'];

        // Nombre d'employés inscrits le mois dernier
        $query = "SELECT COUNT(*) as total FROM collaborateur WHERE date_creation < :lastMonthStart";
        $stmt = $db->prepare($query);
        // Nombre d'employés inscrits le mois dernier
        $query = "SELECT COUNT(*) as total FROM collaborateur WHERE date_creation < :lastMonthStart";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':lastMonthStart' => $lastMonthStart
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['totalLastMonth'] = $result['total'];

        // Calcul de la variation totale en pourcentage
        $totalVariation = $stats['totalLastMonth'] > 0 ?
            round((($stats['total'] - $stats['totalLastMonth']) / $stats['totalLastMonth']) * 100) : 0;
        $stats['totalVariation'] = $totalVariation;

        // Nombre d'employés actifs ce mois
        $query = "SELECT COUNT(DISTINCT collaborateur_id) as active
             FROM collaborateur
             WHERE date_activite >= :currentMonthStart AND date_activite < :nextMonthStart";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':currentMonthStart' => $currentMonthStart,
            ':nextMonthStart' => $nextMonthStart
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['active'] = $result['active'];

        // Nombre d'employés actifs le mois dernier
        $query = "SELECT COUNT(DISTINCT collaborateur_id) as active
             FROM collaborateur
             WHERE date_activite >= :lastMonthStart AND date_activite < :currentMonthStart";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':lastMonthStart' => $lastMonthStart,
            ':currentMonthStart' => $currentMonthStart
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['activeLastMonth'] = $result['active'];

        // Calcul de la variation des actifs en pourcentage
        $activeVariation = $stats['activeLastMonth'] > 0 ?
            round((($stats['active'] - $stats['activeLastMonth']) / $stats['activeLastMonth']) * 100) : 0;
        $stats['activeVariation'] = $activeVariation;

        // Nombre de nouveaux employés ce mois
        $query = "SELECT COUNT(*) as new FROM collaborateur
             WHERE date_creation >= :currentMonthStart AND date_creation < :nextMonthStart";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':currentMonthStart' => $currentMonthStart,
            ':nextMonthStart' => $nextMonthStart
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['new'] = $result['new'];

        // Nombre de nouveaux employés le mois dernier
        $query = "SELECT COUNT(*) as new FROM collaborateur
             WHERE date_creation >= :lastMonthStart AND date_creation < :currentMonthStart";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':lastMonthStart' => $lastMonthStart,
            ':currentMonthStart' => $currentMonthStart
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['newLastMonth'] = $result['new'];

        // Calcul de la variation des nouveaux en pourcentage
        $newVariation = $stats['newLastMonth'] > 0 ?
            round((($stats['new'] - $stats['newLastMonth']) / $stats['newLastMonth']) * 100) : 0;
        $stats['newVariation'] = $newVariation;

        // Taux de participation (basé sur le nombre d'employés actifs divisé par le total)
        $stats['participationRate'] = $stats['total'] > 0 ?
            round(($stats['active'] / $stats['total']) * 100) : 0;

        // Taux de participation le mois dernier
        $participationRateLastMonth = $stats['totalLastMonth'] > 0 ?
            round(($stats['activeLastMonth'] / $stats['totalLastMonth']) * 100) : 0;
        $stats['participationRateLastMonth'] = $participationRateLastMonth;

        // Calcul de la variation du taux de participation
        $participationVariation = $stats['participationRateLastMonth'] > 0 ?
            round(($stats['participationRate'] - $stats['participationRateLastMonth'])) : 0;
        $stats['participationVariation'] = $participationVariation;

        return $stats;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des statistiques : " . $e->getMessage();
    }
}

function getEmployeeProfile(int $id) {
    $db = getDatabaseConnection();
    $sql = "SELECT 
        collaborateur_id,
        nom,
        prenom,
        role,
        email,
        telephone,
        id_societe,
        date_creation,
        date_activite
    FROM collaborateur 
    WHERE collaborateur_id = :id";
    
    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['id' => $id]);
    
    if ($res) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
