<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createEstimate($date_debut, $date_fin, string $statut, float $montant, $is_contract, int $id_societe, $company_name)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO devis (date_debut, date_fin, statut, montant, is_contract,fichier, id_societe) VALUES (:date_debut, :date_fin, :statut, :montant, :is_contract, :fichier, :id_societe)";
    $stmt = $db->prepare($sql);
    if ($is_contract == 1) {
        $fichier = '/contract/' . $company_name . '/' . $date_debut . '_' . $date_fin . '/';
    } else {
        $fichier = '/estimate/' . $company_name . '/' . $date_debut . '_' . $date_fin . '/';
    }
    $res = $stmt->execute([
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'statut' => $statut,
        'montant' => $montant,
        'is_contract' => $is_contract,
        'id_societe' => $id_societe,
        'fichier' => $fichier

    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}

function updateEstimate($date_debut, $date_fin, $statut, $montant, $montant_ht, $montant_tva, $is_contract, $id_societe, $fichier, $devis_id) {
    $db = getDatabaseConnection();

    $sql = "UPDATE devis SET ";
    $params = [];

    if ($date_debut !== null) {
        $sql .= "date_debut = :date_debut, ";
        $params[':date_debut'] = $date_debut;
    }

    if ($date_fin !== null) {
        $sql .= "date_fin = :date_fin, ";
        $params[':date_fin'] = $date_fin;
    }

    if ($statut !== null) {
        $sql .= "statut = :statut, ";
        $params[':statut'] = $statut;
    }

    if ($montant !== null) {
        $sql .= "montant = :montant, ";
        $params[':montant'] = $montant;
    }

    if ($montant_ht !== null) {
        $sql .= "montant_ht = :montant_ht, ";
        $params[':montant_ht'] = $montant_ht;
    }

    if ($montant_tva !== null) {
        $sql .= "montant_tva = :montant_tva, ";
        $params[':montant_tva'] = $montant_tva;
    }

    if ($is_contract !== null) {
        $sql .= "is_contract = :is_contract, ";
        $params[':is_contract'] = $is_contract;
    }

    if ($id_societe !== null) {
        $sql .= "id_societe = :id_societe, ";
        $params[':id_societe'] = $id_societe;
    }

    if ($fichier !== null) {
        $sql .= "fichier = :fichier, ";
        $params[':fichier'] = $fichier;
    }

    // Enlever la virgule et l'espace à la fin
    $sql = rtrim($sql, ", ");

    $sql .= " WHERE devis_id = :devis_id";
    $params[':devis_id'] = $devis_id;

    $stmt = $db->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $result = $stmt->execute($params);

    return $result;
}

function deleteEstimate(int $id)
{
    $db = getDatabaseConnection();
    $sql = "DELETE FROM devis WHERE devis_id=:devis_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        "devis_id" => $id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getEstimateById($id)
{
    $connection = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant,montant_ht,montant_tva, is_contract, fichier, id_societe FROM devis WHERE devis_id = :id ";
    $query = $connection->prepare($sql);
    $res = $query->execute(['id' => $id]);
    if ($res) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

/**
 *tout les params sont optionnels: le premier pour filtrer par username, le deuxième pour définir la limite de résultats et le dernier pour définir où on commence (utile pour la pagination)
 */
function getAllEstimate(int $limit = null, int $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, montant_ht, montant_tva, is_contract, fichier, id_societe FROM devis Where is_contract = 0";
    $params = [];

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
/**
 * tout les params sont optionnels: le premier pour définir la limite de résultats,
 * le deuxième pour définir où on commence (utile pour la pagination)
 * et le troisième pour filtrer par prestataire
 */
function getAllContract(int $limit = null, int $offset = null, int $provider_id = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, montant_ht, montant_tva, is_contract, fichier, id_societe FROM devis Where is_contract = 1 AND date_fin > NOW()";
    $params = [];

    // Filtrage par prestataire
    if ($provider_id !== null) {
        $sql .= " AND id_societe = :provider_id";
        $params[':provider_id'] = $provider_id;
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

function getAllContractExpired(int $limit = null, int $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT devis_id, date_debut, date_fin, statut, montant, montant_ht, montant_tva, is_contract, fichier, id_societe FROM devis WHERE is_contract = 1 AND date_fin < NOW()";
    $params = [];

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

function getContractStats()
{
    $db = getDatabaseConnection();

    // Nombre total de devis (incluant contrats et non-contrats)
    $sqlTotalEstimates = "SELECT COUNT(devis_id) as total FROM devis";
    $stmtTotalEstimates = $db->prepare($sqlTotalEstimates);
    $stmtTotalEstimates->execute();
    $totalEstimates = $stmtTotalEstimates->fetch(PDO::FETCH_ASSOC)['total'];

    // Nombre de contrats actifs (dont la date de fin est postérieure à la date actuelle)
    $sqlActiveContracts = "SELECT COUNT(devis_id) as total FROM devis WHERE is_contract = 1 AND date_fin > NOW()";
    $stmtActiveContracts = $db->prepare($sqlActiveContracts);
    $stmtActiveContracts->execute();
    $activeContracts = $stmtActiveContracts->fetch(PDO::FETCH_ASSOC)['total'];

    // Montant total des contrats du mois en cours
    $currentMonth = date('Y-m');
    $sqlMonthlyContractsAmount = "SELECT SUM(montant) as total FROM devis WHERE is_contract = 1 AND DATE_FORMAT(date_debut, '%Y-%m') = :currentMonth";
    $stmtMonthlyContractsAmount = $db->prepare($sqlMonthlyContractsAmount);
    $stmtMonthlyContractsAmount->execute(['currentMonth' => $currentMonth]);
    $monthlyContractsAmount = $stmtMonthlyContractsAmount->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;

    // Nombre de contrats (pour calculer le taux de conversion)
    $sqlContracts = "SELECT COUNT(devis_id) as total FROM devis WHERE is_contract = 1";
    $stmtContracts = $db->prepare($sqlContracts);
    $stmtContracts->execute();
    $totalContracts = $stmtContracts->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcul du taux de conversion
    $conversionRate = 0;
    if ($totalEstimates > 0) {
        $conversionRate = ($totalContracts / $totalEstimates) * 100;
    }

    return [
        'devis_totaux' => $totalEstimates,
        'contrats_actifs' => $activeContracts,
        'montant_total_contrats_mois' => round($monthlyContractsAmount, 2),
        'taux_conversion' => round($conversionRate, 2)
    ];
}

function modifyEstimateState($id, $state)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE devis SET statut = :statut WHERE devis_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'id' => $id,
        'statut' => $state
    ]);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function isValidEstimateStatus($status)
{
    if ($status === null) {
        return false;
    }
    $validStatuses = ['brouillon', 'envoyé', 'accepté', 'refusé'];
    return in_array($status, $validStatuses);
}

function convertToContract($devis_id)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE devis SET is_contract = 1, statut = 'accepté' WHERE devis_id = :devis_id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'devis_id' => $devis_id
    ]);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function getContractByProvider(int $provider_id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT DISTINCT d.devis_id, d.date_debut, d.date_fin, d.statut, d.montant, d.montant_ht, d.montant_tva,
                   d.is_contract, d.fichier, d.id_societe
            FROM devis d
            INNER JOIN facture f ON d.devis_id = f.id_devis
            WHERE d.is_contract = 1 AND f.id_prestataire = :provider_id";

    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'provider_id' => $provider_id
    ]);

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}
