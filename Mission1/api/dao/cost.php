<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createCost($societyId, $name, $amount, $invoiceId)
{
    try {
        $db = getDatabaseConnection();
        $sql = "INSERT INTO other_costs (society_id, name, amount, invoice_id, created_at) VALUES (:society_id, :name, :amount, :invoice_id, NOW())";
        $stmt = $db->prepare($sql);

        $res = $stmt->execute([
            'society_id' => $societyId,
            'name' => $name,
            'amount' => $amount,
            'invoice_id' => $invoiceId
        ]);

        if ($res) {
            return $db->lastInsertId();
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la création du frais : " . $e->getMessage();
        return false;
    }
}

function getAllCosts($societyId)
{
    try {
        $db = getDatabaseConnection();
        $sql = "SELECT id, name, amount, invoice_id, created_at FROM other_costs WHERE society_id = :society_id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['society_id' => $societyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des frais : " . $e->getMessage();
        return [];
    }
}

function getCostById($costId)
{
    try {
        $db = getDatabaseConnection();
        $sql = "SELECT id, society_id, name, amount, invoice_id, created_at FROM other_costs WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $costId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération du frais : " . $e->getMessage();
        return null;
    }
}

function updateCost($costId, ?string $name = null, ?float $amount = null, ?int $invoiceId = null)
{
    $db = getDatabaseConnection();

    $currentCost = getCostById($costId);
    if (!$currentCost) {
        return null; // Frais introuvable
    }

    $params = ['id' => $costId];
    $setFields = [];

    if ($name !== null) {
        $setFields[] = "name = :name";
        $params['name'] = $name;
    }

    if ($amount !== null) {
        $setFields[] = "amount = :amount";
        $params['amount'] = $amount;
    }

    if ($invoiceId !== null) {
        $setFields[] = "invoice_id = :invoice_id";
        $params['invoice_id'] = $invoiceId;
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE other_costs SET " . implode(", ", $setFields) . " WHERE id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);

    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function deleteCost($costId)
{
    try {
        $db = getDatabaseConnection();
        $sql = "DELETE FROM other_costs WHERE id = :id";
        $stmt = $db->prepare($sql);
        $res = $stmt->execute(['id' => $costId]);
        return $res ? $stmt->rowCount() : null;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du frais : " . $e->getMessage();
        return null;
    }
}
