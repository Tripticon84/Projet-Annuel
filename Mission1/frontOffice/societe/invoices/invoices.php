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
                <button type="button" class="btn btn-warning" id="editInvoice">Modifier</button>
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

        // Calcul automatique du montant TTC
        document.getElementById('montant_ht').addEventListener('input', calculateTTC);
        document.getElementById('taux_tva').addEventListener('input', calculateTTC);
    });

    // Fonction pour calculer le montant TTC
    function calculateTTC() {
        const montantHT = parseFloat(document.getElementById('montant_ht').value) || 0;
        const tauxTVA = parseFloat(document.getElementById('taux_tva').value) || 0;

        const montantTVA = montantHT * (tauxTVA / 100);
        const montantTTC = montantHT + montantTVA;

        document.getElementById('montant_ttc').value = montantTTC.toFixed(2);
    }

    // Fonction pour charger toutes les factures
    function loadAllInvoices(societyId) {
        fetch(`/api/company/getInvoices.php?societe_id=${societyId}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('invoices-table');

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Aucune facture trouvée</td></tr>';
                return;
            }

            tableBody.innerHTML = '';
            data.forEach(invoice => {
                const statusClass = getStatusBadge(invoice.statut);
                tableBody.innerHTML += `
                    <tr>
                        <td>${invoice.facture_id}</td>
                        <td>${new Date(invoice.date_emission).toLocaleDateString('fr-FR')}</td>
                        <td>${new Date(invoice.date_echeance).toLocaleDateString('fr-FR')}</td>
                        <td>${invoice.montant.toLocaleString('fr-FR')} €</td>
                        <td>${invoice.montant_tva.toLocaleString('fr-FR')} €</td>
                        <td>${invoice.montant_ht.toLocaleString('fr-FR')} €</td>
                        <td><span class="badge bg-${statusClass}">${invoice.statut}</span></td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewInvoiceDetails(${invoice.facture_id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${invoice.statut !== 'Payee' ?
                                `<button class="btn btn-sm btn-success" onclick="markInvoiceAsPaid(${invoice.facture_id})">
                                    <i class="fas fa-check"></i>
                                </button>` : ''}
                            <button class="btn btn-sm btn-danger" onclick="downloadInvoicePDF(${invoice.facture_id})">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des factures:', error);
            document.getElementById('invoices-table').innerHTML =
                `<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des factures</td></tr>`;
        });
    }

    // Fonction pour appliquer les filtres
    function applyInvoiceFilters() {
        const status = document.getElementById('statusFilter').value;
        const startDate = document.getElementById('dateStartFilter').value;
        const endDate = document.getElementById('dateEndFilter').value;

        // Construire l'URL avec les filtres
        let url = `/api/company/getInvoices.php?societe_id=${societyId}`;
        if (status) url += `&status=${status}`;
        if (startDate) url += `&start_date=${startDate}`;
        if (endDate) url += `&end_date=${endDate}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('invoices-table');

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Aucune facture trouvée avec ces critères</td></tr>';
                return;
            }

            // Afficher les résultats filtrés (même code que dans loadAllInvoices)
            tableBody.innerHTML = '';
            data.forEach(invoice => {
                const statusClass = getStatusBadge(invoice.statut);
                tableBody.innerHTML += `
                    <tr>
                        <td>${invoice.facture_id}</td>
                        <td>${new Date(invoice.date_emission).toLocaleDateString('fr-FR')}</td>
                        <td>${new Date(invoice.date_echeance).toLocaleDateString('fr-FR')}</td>
                        <td>${invoice.montant.toLocaleString('fr-FR')} €</td>
                        <td>${invoice.montant_tva.toLocaleString('fr-FR')} €</td>
                        <td>${invoice.montant_ht.toLocaleString('fr-FR')} €</td>
                        <td><span class="badge bg-${statusClass}">${invoice.statut}</span></td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewInvoiceDetails(${invoice.facture_id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${invoice.statut !== 'Payee' ?
                                `<button class="btn btn-sm btn-success" onclick="markInvoiceAsPaid(${invoice.facture_id})">
                                    <i class="fas fa-check"></i>
                                </button>` : ''}
                            <button class="btn btn-sm btn-danger" onclick="downloadInvoicePDF(${invoice.facture_id})">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Erreur lors de l\'application des filtres:', error);
            document.getElementById('invoices-table').innerHTML =
                `<tr><td colspan="8" class="text-center text-danger">Erreur lors de l'application des filtres</td></tr>`;
        });
    }

    // Fonction pour voir les détails d'une facture
    function viewInvoiceDetails(id) {
        fetch(`/api/company/getInvoiceDetails.php?invoice_id=${id}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(invoice => {
            const detailsContainer = document.getElementById('invoice-details');
            detailsContainer.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> ${invoice.facture_id}</p>
                        <p><strong>Date d'émission:</strong> ${new Date(invoice.date_emission).toLocaleDateString('fr-FR')}</p>
                        <p><strong>Date d'échéance:</strong> ${new Date(invoice.date_echeance).toLocaleDateString('fr-FR')}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Montant HT:</strong> ${invoice.montant_ht.toLocaleString('fr-FR')} €</p>
                        <p><strong>TVA:</strong> ${invoice.montant_tva.toLocaleString('fr-FR')} €</p>
                        <p><strong>Montant TTC:</strong> ${invoice.montant.toLocaleString('fr-FR')} €</p>
                        <p><strong>Statut:</strong> <span class="badge bg-${getStatusBadge(invoice.statut)}">${invoice.statut}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Description:</strong></p>
                        <p>${invoice.description || 'Aucune description disponible'}</p>
                    </div>
                </div>
            `;

            // Désactiver le bouton "Marquer comme payée" si déjà payée
            const markAsPaidBtn = document.getElementById('markAsPaid');
            if (invoice.statut === 'Payee') {
                markAsPaidBtn.disabled = true;
                markAsPaidBtn.classList.add('disabled');
            } else {
                markAsPaidBtn.disabled = false;
                markAsPaidBtn.classList.remove('disabled');
                markAsPaidBtn.setAttribute('data-id', invoice.facture_id);
            }

            // Stocker l'ID pour l'édition
            document.getElementById('editInvoice').setAttribute('data-id', invoice.facture_id);

            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur lors du chargement des détails de la facture:', error);
            alert('Erreur lors du chargement des détails de la facture');
        });
    }

    // Fonction pour marquer une facture comme payée
    function markInvoiceAsPaid(id) {
        if (confirm('Êtes-vous sûr de vouloir marquer cette facture comme payée ?')) {
            fetch(`/api/company/updateInvoiceStatus.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + getToken()
                },
                body: JSON.stringify({
                    facture_id: id,
                    statut: 'Payee'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut de la facture mis à jour avec succès !');

                    // Fermer le modal de détails s'il est ouvert
                    const detailModal = bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal'));
                    if (detailModal) {
                        detailModal.hide();
                    }

                    // Recharger les factures
                    loadAllInvoices(societyId);
                } else {
                    alert(`Erreur: ${data.message || 'Une erreur est survenue'}`);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour du statut:', error);
                alert('Une erreur est survenue lors de la mise à jour du statut');
            });
        }
    }

    // Fonction pour télécharger une facture en PDF
    function downloadInvoicePDF(id) {
        window.open(`/api/company/generateInvoicePDF.php?invoice_id=${id}&token=${getToken()}`, '_blank');
    }

    // Fonction pour ajouter une nouvelle facture
    function addNewInvoice() {
        const formData = {
            societe_id: societyId,
            date_emission: document.getElementById('date_emission').value,
            date_echeance: document.getElementById('date_echeance').value,
            montant_ht: document.getElementById('montant_ht').value,
            taux_tva: document.getElementById('taux_tva').value,
            montant_ttc: document.getElementById('montant_ttc').value,
            description: document.getElementById('description').value,
            statut: 'Attente'  // Par défaut, une nouvelle facture est en attente
        };

        fetch('/api/company/addInvoice.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + getToken()
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addInvoiceModal'));
                modal.hide();

                // Réinitialiser le formulaire
                document.getElementById('addInvoiceForm').reset();

                // Recharger les factures
                loadAllInvoices(societyId);

                // Afficher un message de succès
                alert('Facture ajoutée avec succès!');
            } else {
                alert(`Erreur: ${data.message || 'Une erreur est survenue'}`);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout de la facture:', error);
            alert('Une erreur est survenue lors de l\'ajout de la facture');
        });
    }
</script>
