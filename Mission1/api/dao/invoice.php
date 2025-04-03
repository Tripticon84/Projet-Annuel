<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/ressources/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;


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


function getAllInvoice( $id_prestataire = null,  $limit = null,  $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture";
    $params = [];

    if (!empty($id_prestataire)) {
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

function getAllInvoiceByState($state, $id_prestataire = "", int $limit = null, int $offset = null)
{
    $db = getDatabaseConnection();
    $sql = "SELECT facture_id, date_emission, date_echeance, montant, montant_tva, montant_ht, statut, methode_paiement, id_devis, id_prestataire FROM facture WHERE statut = :statut";
    $params = ['statut' => $state];

    if (!empty($id_prestataire)) {
        $sql .= " AND id_prestataire LIKE :id_prestataire";
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

function getInvoiceByProvider($providerId)
{
    $db = getDatabaseConnection();
    $sql = "SELECT f.facture_id, f.date_emission, f.date_echeance, f.montant, f.montant_tva, f.montant_ht,
           f.statut, f.methode_paiement, f.id_devis, f.id_prestataire,
           p.nom, p.prenom, p.email, p.type
           FROM facture f
           JOIN prestataire p ON f.id_prestataire = p.prestataire_id
           WHERE f.id_prestataire = :providerId";

    $stmt = $db->prepare($sql);
    $res = $stmt->execute(['providerId' => $providerId]);

    if ($res) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProviderByInvoice($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT p.prestataire_id, p.nom, p.prenom, p.email, p.type, p.tarif, p.date_debut_disponibilite, p.date_fin_disponibilite, p.description FROM prestataire p JOIN facture f ON p.prestataire_id = f.id_prestataire WHERE f.facture_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function getCompanyByInvoice($id)
{
    $db = getDatabaseConnection();
    $sql = "SELECT c.societe_id, c.nom, c.siret, c.email, c.adresse, c.date_creation, c.concact_person, c.telephone 
            FROM societe c 
            JOIN devis d ON c.societe_id = d.id_societe 
            JOIN facture f ON d.devis_id = f.id_devis 
            WHERE f.facture_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
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

function updateInvoice($id,  $date_emission = null,  $date_echeance = null, $montant = null,  $montant_tva = null,  $montant_ht = null,  $statut = null,  $methode_paiement = null,  $id_devis = null,  $id_prestataire = null)
{
    $db = getDatabaseConnection();
    $params = ['id' => $id];
    $setFields = [];

    if ($date_emission !== null) {
        $setFields[] = "date_emission = :date_emission";
        $params['date_emission'] = $date_emission;
    }

    if ($date_echeance !== null) {
        $setFields[] = "date_echeance = :date_echeance";
        $params['date_echeance'] = $date_echeance;
    }

    if ($montant !== null) {
        $setFields[] = "montant = :montant";
        $params['montant'] = $montant;
    }

    if ($montant_tva !== null) {
        $setFields[] = "montant_tva = :montant_tva";
        $params['montant_tva'] = $montant_tva;
    }

    if ($montant_ht !== null) {
        $setFields[] = "montant_ht = :montant_ht";
        $params['montant_ht'] = $montant_ht;
    }

    if ($statut !== null) {
        $setFields[] = "statut = :statut";
        $params['statut'] = $statut;
    }

    if ($methode_paiement !== null) {
        $setFields[] = "methode_paiement = :methode_paiement";
        $params['methode_paiement'] = $methode_paiement;
    }

    if ($id_devis !== null) {
        $setFields[] = "id_devis = :id_devis";
        $params['id_devis'] = $id_devis;
    }

    if ($id_prestataire !== null) {
        $setFields[] = "id_prestataire = :id_prestataire";
        $params['id_prestataire'] = $id_prestataire;
    }

    if (empty($setFields)) {
        return 0; // Rien à mettre à jour
    }

    $sql = "UPDATE facture SET " . implode(", ", $setFields) . " WHERE facture_id = :id";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute($params);
    if ($res) {
        return $stmt->rowCount();
    }
    return null;
}

function isValidStatus($status)
{
    if ($status === null) {
        return false;
    }
    if ($status === "Attente" || $status === "Payee" || $status === "Annulee") {
        return true;
    }
    return false;
}


function generatePDFForProvider($factureId){
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    $provider =  getProviderByInvoice($factureId);
    if ($provider === null) {
        returnError(404, 'Provider not found');
    }
    echo json_encode($provider);
    $infos = getInvoiceByProvider($provider['prestataire_id']);
    if ($infos === null) {
        returnError(404, 'Invoice not found');
    }
    
    echo json_encode($infos);

    $html = '<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            h1, h2, h3 {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Provider Information</h1>
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>';
    
    foreach ($provider as $key => $value) {
        $html .= '<tr>
            <td>' . htmlspecialchars($key) . '</td>
            <td>' . htmlspecialchars($value) . '</td>
        </tr>';
    }

    $html .= '</table>
        <h2>Invoices</h2>
        <table>
            <tr>
                <th>Facture ID</th>
                <th>Date Emission</th>
                <th>Date Echeance</th>
                <th>Montant</th>
                <th>Montant TVA</th>
                <th>Montant HT</th>
                <th>Statut</th>
                <th>Methode Paiement</th>
                <th>ID Devis</th>
                <th>ID Prestataire</th>
            </tr>';

    foreach ($infos as $info) {
        $html .= '<tr>
            <td>' . htmlspecialchars($info['facture_id']) . '</td>
            <td>' . htmlspecialchars($info['date_emission']) . '</td>
            <td>' . htmlspecialchars($info['date_echeance']) . '</td>
            <td>' . htmlspecialchars($info['montant']) . '</td>
            <td>' . htmlspecialchars($info['montant_tva']) . '</td>
            <td>' . htmlspecialchars($info['montant_ht']) . '</td>
            <td>' . htmlspecialchars($info['statut']) . '</td>
            <td>' . htmlspecialchars($info['methode_paiement']) . '</td>
            <td>' . htmlspecialchars($info['id_devis']) . '</td>
            <td>' . htmlspecialchars($info['id_prestataire']) . '</td>
        </tr>';
    }

    $html .= '</table>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("provider_invoices.pdf", ["Attachment" => false]); // false = affiche dans le navigateur
    
}

function generatePDFForCompany($factureId){
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    $company =  getCompanyByInvoice($factureId);
    if ($company === null) {
        returnError(404, 'company not found');
    }
    echo json_encode($company);
    $infos = getInvoiceById($factureId);
    if ($infos === null) {
        returnError(404, 'Invoice not found');
    }
    
    echo json_encode($infos);
    $html = '<html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h1, h2, h3 {
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h1>Company Information</h1>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>';
        
        foreach ($company as $key => $value) {
            $html .= '<tr>
                <td>' . htmlspecialchars($key) . '</td>
                <td>' . htmlspecialchars($value) . '</td>
            </tr>';
        }

        $html .= '</table>
            <h2>Invoice Details</h2>
            <table>
                <tr>
                    <th>Facture ID</th>
                    <th>Date Emission</th>
                    <th>Date Echeance</th>
                    <th>Montant</th>
                    <th>Montant TVA</th>
                    <th>Montant HT</th>
                    <th>Statut</th>
                    <th>Methode Paiement</th>
                    <th>ID Devis</th>
                    <th>ID Prestataire</th>
                </tr>';

        $html .= '<tr>
            <td>' . htmlspecialchars($infos['facture_id']) . '</td>
            <td>' . htmlspecialchars($infos['date_emission']) . '</td>
            <td>' . htmlspecialchars($infos['date_echeance']) . '</td>
            <td>' . htmlspecialchars($infos['montant']) . '</td>
            <td>' . htmlspecialchars($infos['montant_tva']) . '</td>
            <td>' . htmlspecialchars($infos['montant_ht']) . '</td>
            <td>' . htmlspecialchars($infos['statut']) . '</td>
            <td>' . htmlspecialchars($infos['methode_paiement']) . '</td>
            <td>' . htmlspecialchars($infos['id_devis']) . '</td>
            <td>' . htmlspecialchars($infos['id_prestataire']) . '</td>
        </tr>';

        $html .= '</table>
        </body>
        </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("provider_invoices.pdf", ["Attachment" => false]); // false = affiche dans le navigateur
}
