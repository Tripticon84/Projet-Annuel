<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";

function createInvoice($date_emission,$date_echeance,$montant,$montant_tva,$montant_ht,$statut,$methode_paiement,$id_devis,$id_prestataire)
{
    $db = getDatabaseConnection();
    $sql = "INSERT INTO facture (date_emission, date_echeance, montant, montant_tva, montant_ht,statut, methode_paiement,id_devis,id_prestataire) VALUES (:date_emission, :date_echance, :montant, :montant_tva, :montant_ht,
    :statut, :methode_paiement, :id_devis, :id_prestataire);";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        'date_emission' => $date_emission,
        'date_echance' => $date_echeance,
        'montant' => $montant,
        'montant_tva' => $montant_tva,
        'montant_ht' => $montant_ht,
        'statut' => $statut,
        'methode_paiement' => $methode_paiement,
        'id_devis' => $id_devis,
        'id_prestataire' => $id_prestataire

        
    ]);
    if ($res) {
        return $db->lastInsertId();
    }
    return null;
}


function getAllInvoice( $id_prestataire = "", int $limit = null, int $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture";
    $params = [];

    if (!empty($adress)) {
        $sql .= " WHERE id_prestataire LIKE :id_prestataire";
        $params['id_prestataire'] = "%" . $id_prestataire . "%";
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

function getInvoiceById($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture WHERE facture_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function getInvoiceByProviderId($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture WHERE id_prestataire = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetchAll();
}

function getInvoiceByEstimateId($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture WHERE id_devis = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetchAll();
}

function modifyState($id,$state)
{
    $db = getDatabaseConnection();
    $sql = "UPDATE facture SET statut = :statut WHERE facture_id = :id";
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