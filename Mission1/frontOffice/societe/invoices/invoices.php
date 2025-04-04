<?php
$title = "Gestion des Factures";

include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/head.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Inclusion de la sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des Factures</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshData">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                            <i class="fas fa-plus"></i> Nouvelle facture
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtres de recherche -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Filtres</h5>
                </div>
                <div class="card-body">
                    <form id="invoiceFilterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Statut</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">Tous</option>
                                <option value="Payee">Payée</option>
                                <option value="Attente">En attente</option>
                                <option value="Annulé">Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateStartFilter" class="form-label">Date d'émission</label>
                            <input type="date" class="form-control" id="dateStartFilter">
                        </div>
                        <div class="col-md-3">
                            <label for="dateEndFilter" class="form-label">Date d'échéance</label>
                            <input type="date" class="form-control" id="dateEndFilter">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="applyFilters" class="btn btn-primary">Appliquer</button>
                            <button type="button" id="resetFilters" class="btn btn-secondary ms-2">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des factures -->
            <div class="card">
                <div class="card-header">
                    <h5>Liste des factures</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date d'émission</th>
                                    <th>Date d'échéance</th>
                                    <th>Montant TTC</th>
                                    <th>TVA</th>
                                    <th>Montant HT</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="invoices-table">
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement des factures...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal pour ajouter une facture -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInvoiceModalLabel">Ajouter une facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addInvoiceForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date_emission" class="form-label">Date d'émission</label>
                            <input type="date" class="form-control" id="date_emission" name="date_emission" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_echeance" class="form-label">Date d'échéance</label>
                            <input type="date" class="form-control" id="date_echeance" name="date_echeance" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="montant_ht" class="form-label">Montant HT</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="montant_ht" name="montant_ht" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="taux_tva" class="form-label">Taux TVA</label>
                            <div class="input-group">
                                <input type="number" step="0.1" class="form-control" id="taux_tva" name="taux_tva" value="20.0" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="montant_ttc" class="form-label">Montant TTC (calculé)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="montant_ttc" name="montant_ttc" readonly>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveInvoice">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour visualiser/modifier une facture -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewInvoiceModalLabel">Détails de la facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="invoice-details">
                <!-- Le contenu sera injecté dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="markAsPaid">Marquer comme payée</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let societyId = <?php echo $_SESSION['societe_id']; ?>;

    // Fonction d'initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Charger toutes les factures
        loadAllInvoices(societyId);

        // Configuration des événements
        document.getElementById('refreshData').addEventListener('click', function() {
            loadAllInvoices(societyId);
        });

        document.getElementById('applyFilters').addEventListener('click', function() {
            applyInvoiceFilters();
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('invoiceFilterForm').reset();
            loadAllInvoices(societyId);
        });

        document.getElementById('saveInvoice').addEventListener('click', function() {
            addNewInvoice();
        });

    });


</script>
