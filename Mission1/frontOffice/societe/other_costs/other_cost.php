<?php
$title = "Gestion des Autres Frais";

include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/head.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Inclusion de la sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des Autres Frais</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshData">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addOtherCostModal">
                            <i class="fas fa-plus"></i> Nouveau frais
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
                    <form id="otherCostFilterForm" class="row g-3">
                        <div class="col-md-4">
                            <label for="nameFilter" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nameFilter">
                        </div>
                        <div class="col-md-4">
                            <label for="dateStartFilter" class="form-label">Date de création (depuis)</label>
                            <input type="date" class="form-control" id="dateStartFilter">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" id="applyFilters" class="btn btn-primary">Appliquer</button>
                            <button type="button" id="resetFilters" class="btn btn-secondary ms-2">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des autres frais -->
            <div class="card">
                <div class="card-header">
                    <h5>Liste des autres frais</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Montant</th>
                                    <th>Facture associée</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="costs-table">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement des frais...</span>
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

<!-- Modal pour ajouter un frais -->
<div class="modal fade" id="addOtherCostModal" tabindex="-1" aria-labelledby="addOtherCostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOtherCostModalLabel">Ajouter un frais</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOtherCostForm">
                    <div class="mb-3">
                        <label for="cost_name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="cost_name" name="cost_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost_montant" class="form-label">Montant</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="cost_montant" name="cost_montant" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cost_facture" class="form-label">Facture associée</label>
                        <select class="form-select" id="cost_facture" name="cost_facture" required>
                            <option value="">Sélectionner une facture</option>
                            <!-- Les options seront chargées dynamiquement -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveOtherCost">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour visualiser/modifier un frais -->
<div class="modal fade" id="viewOtherCostModal" tabindex="-1" aria-labelledby="viewOtherCostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOtherCostModalLabel">Détails du frais</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="otherCost-details">
                <!-- Le contenu sera injecté dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" id="editOtherCost">Modifier</button>
                <button type="button" class="btn btn-danger" id="deleteOtherCost">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer un frais -->
<div class="modal fade" id="editOtherCostModal" tabindex="-1" aria-labelledby="editOtherCostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOtherCostModalLabel">Modifier le frais</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOtherCostForm">
                    <input type="hidden" id="edit_other_cost_id">
                    <div class="mb-3">
                        <label for="edit_cost_name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_cost_name" name="edit_cost_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cost_montant" class="form-label">Montant</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="edit_cost_montant" name="edit_cost_montant" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cost_facture" class="form-label">Facture associée</label>
                        <select class="form-select" id="edit_cost_facture" name="edit_cost_facture" required>
                            <option value="">Sélectionner une facture</option>
                            <!-- Les options seront chargées dynamiquement -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateOtherCost">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let societyId = <?php echo $_SESSION['societe_id']; ?>;

    // Fonction d'initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Charger tous les autres frais
        loadOtherCosts(societyId);

        // Charger les factures pour les selects
        loadInvoicesForSelect();

        // Configuration des événements
        document.getElementById('refreshData').addEventListener('click', function() {
            loadOtherCosts(societyId);
        });

        document.getElementById('applyFilters').addEventListener('click', function() {
            applyOtherCostFilters();
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('otherCostFilterForm').reset();
            loadOtherCosts(societyId);
        });

        document.getElementById('saveOtherCost').addEventListener('click', function() {
            addNewOtherCost();
        });

        document.getElementById('editOtherCost').addEventListener('click', function() {
            const costId = this.getAttribute('data-id');
            editOtherCostDetails(costId);
        });

        document.getElementById('deleteOtherCost').addEventListener('click', function() {
            const costId = this.getAttribute('data-id');
            deleteOtherCost(costId);
        });

        document.getElementById('updateOtherCost').addEventListener('click', function() {
            updateOtherCostDetails();
        });
    });

    // Ajouter une gestion des erreurs pour le chargement des frais
    function loadOtherCosts(societyId) {
        fetch(`/api/other_costs?society_id=${societyId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des frais.');
                }
                return response.json();
            })
            .then(data => {
                // ...existing code to populate the table...
            })
            .catch(error => {
                console.error(error);
                document.getElementById('costs-table').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Erreur lors du chargement des frais. Veuillez réessayer plus tard.
                        </td>
                    </tr>
                `;
            });
    }
</script>
