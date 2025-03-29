<?php
$title = "Modifier une facture";
include_once "../includes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include_once "../includes/sidebar.php"; ?>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Modifier une facture</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="invoice.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Informations de la facture</h5>
                    </div>
                    <div class="card-body">
                        <div id="loadingMessage" class="alert alert-info">
                            Chargement des données de la facture...
                        </div>
                        <div id="errorMessage" class="alert alert-danger d-none">
                            Erreur lors du chargement des données de la facture.
                        </div>
                        <form id="modifyInvoiceForm" class="d-none">
                            <input type="hidden" id="facture_id" name="facture_id">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_emission" class="form-label">Date d'émission <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_emission" name="date_emission" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_echeance" class="form-label">Date d'échéance <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_echeance" name="date_echeance" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_prestataire" class="form-label">Prestataire <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_prestataire" name="id_prestataire" required>
                                        <option value="">Sélectionner un prestataire</option>
                                        <!-- Les options seront chargées dynamiquement -->
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="id_devis" class="form-label">Devis associé <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_devis" name="id_devis" required>
                                        <option value="">Sélectionner un devis</option>
                                        <!-- Les options seront chargées dynamiquement -->
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="montant_ht" class="form-label">Montant HT (€)</label>
                                    <input type="number" class="form-control" id="montant_ht" name="montant_ht" step="0.01" min="0" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="montant_tva" class="form-label">Montant TVA (€)</label>
                                    <input type="number" class="form-control" id="montant_tva" name="montant_tva" step="0.01" min="0" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="montant" class="form-label">Montant TTC (€)</label>
                                    <input type="number" class="form-control" id="montant" name="montant" step="0.01" min="0" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="statut" name="statut" required>
                                        <option value="Attente">En attente</option>
                                        <option value="Payee">Payée</option>
                                        <option value="Annulee">Annulée</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="methode_paiement" class="form-label">Méthode de paiement</label>
                                    <select class="form-select" id="methode_paiement" name="methode_paiement">
                                        <option value="">Sélectionner</option>
                                        <option value="Carte bancaire">Carte bancaire</option>
                                        <option value="Virement">Virement bancaire</option>
                                        <option value="Chèque">Chèque</option>
                                        <option value="Espèces">Espèces</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-secondary" id="resetBtn">Réinitialiser</button>
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer l'ID de la facture depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const invoiceId = urlParams.get('id');

            if (!invoiceId) {
                showError("ID de facture non spécifié. Veuillez sélectionner une facture à modifier.");
                return;
            }

            // Définir l'ID de la facture dans le formulaire
            document.getElementById('facture_id').value = invoiceId;

            // Charger les données de la facture
            fetchInvoiceDetails(invoiceId);

            // Réinitialiser le formulaire avec les données originales
            document.getElementById('resetBtn').addEventListener('click', function() {
                fetchInvoiceDetails(invoiceId);
            });

            // Soumission du formulaire
            const form = document.getElementById('modifyInvoiceForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                const formData = new FormData(form);
                const invoiceData = {};

                formData.forEach((value, key) => {
                    invoiceData[key] = value;
                });

                fetch('../../api/invoice/modify.php', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + getToken()
                    },
                    body: JSON.stringify(invoiceData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.facture_id) {
                        alert('Facture modifiée avec succès!');
                        window.location.href = 'invoice.php';
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de modifier la facture'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la modification de la facture.');
                });
            });
        });

        function fetchInvoiceDetails(invoiceId) {
            fetch(`../../api/invoice/getOne.php?id=${invoiceId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Facture non trouvée');
                }
                return response.json();
            })
            .then(data => {
                // Remplir le formulaire avec les données de la facture
                fillForm(data);

                // Charger les prestataires et sélectionner celui de la facture
                fetchProviders(data.id_prestataire);

                // Afficher le formulaire
                document.getElementById('loadingMessage').classList.add('d-none');
                document.getElementById('modifyInvoiceForm').classList.remove('d-none');
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError("Impossible de charger les détails de la facture. " + error.message);
            });
        }

        function fillForm(invoice) {
            document.getElementById('date_emission').value = formatDateForInput(invoice.date_emission);
            document.getElementById('date_echeance').value = formatDateForInput(invoice.date_echeance);
            document.getElementById('montant_ht').value = invoice.montant_ht;
            document.getElementById('montant_tva').value = invoice.montant_tva;
            document.getElementById('montant').value = invoice.montant;
            document.getElementById('statut').value = invoice.statut;
            document.getElementById('methode_paiement').value = invoice.methode_paiement || '';
        }

        function fetchProviders(selectedProviderId) {
            fetch('../../api/provider/getVerifiedProviders.php', {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                const providerSelect = document.getElementById('id_prestataire');
                providerSelect.innerHTML = '<option value="">Sélectionner un prestataire</option>';

                if (data && data.length > 0) {
                    data.forEach(provider => {
                        const option = document.createElement('option');
                        option.value = provider.id;
                        option.textContent = `${provider.name} ${provider.surname}`;
                        providerSelect.appendChild(option);
                    });

                    // Sélectionner le prestataire de la facture
                    providerSelect.value = selectedProviderId;

                    // Déclencher l'événement change pour charger les devis
                    const event = new Event('change');
                    providerSelect.dispatchEvent(event);
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des prestataires:', error);
            });

            // Ajouter un écouteur d'événements pour charger les devis lorsqu'un prestataire est sélectionné
            document.getElementById('id_prestataire').addEventListener('change', function() {
                const providerId = this.value;
                if (providerId) {
                    fetchEstimates(providerId);
                } else {
                    // Réinitialiser la liste des devis si aucun prestataire n'est sélectionné
                    const estimateSelect = document.getElementById('id_devis');
                    estimateSelect.innerHTML = '<option value="">Sélectionner un devis</option>';
                }
            });
        }

        function fetchEstimates(providerId) {
            const invoiceId = document.getElementById('facture_id').value;

            fetch(`../../api/invoice/getOne.php?id=${invoiceId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
            .then(response => response.json())
            .then(invoice => {
                // Stocker l'ID du devis original
                const originalEstimateId = invoice.id_devis;

                // Maintenant, récupérer tous les devis du prestataire
                return fetch(`../../api/estimate/getByProvider.php?id_prestataire=${providerId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + getToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const estimateSelect = document.getElementById('id_devis');
                    estimateSelect.innerHTML = '<option value="">Sélectionner un devis</option>';

                    if (data && data.length > 0) {
                        // Inclure tous les devis, pas seulement ceux qui sont des contrats
                        data.forEach(estimate => {
                            const option = document.createElement('option');
                            option.value = estimate.devis_id;
                            option.textContent = `Devis #${estimate.devis_id} - ${estimate.montant} €`;
                            estimateSelect.appendChild(option);
                        });

                        // Sélectionner le devis original
                        estimateSelect.value = originalEstimateId;

                        // Déclencher l'événement change pour charger les détails du devis
                        const event = new Event('change');
                        estimateSelect.dispatchEvent(event);
                    }
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des devis:', error);
            });

            // Ajouter un écouteur d'événements pour charger les détails du devis lorsqu'un devis est sélectionné
            document.getElementById('id_devis').addEventListener('change', function() {
                const estimateId = this.value;
                if (estimateId) {
                    fetchEstimateDetails(estimateId);
                } else {
                    // Réinitialiser les montants
                    document.getElementById('montant_ht').value = '';
                    document.getElementById('montant_tva').value = '';
                    document.getElementById('montant').value = '';
                }
            });
        }

        function fetchEstimateDetails(estimateId) {
            fetch(`../../api/estimate/getOne.php?id=${estimateId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Remplir automatiquement les champs de montant
                    document.getElementById('montant_ht').value = data.montant_ht || 0;
                    document.getElementById('montant_tva').value = data.montant_tva || 0;
                    document.getElementById('montant').value = data.montant || 0;
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des détails du devis:', error);
            });
        }

        function validateForm() {
            const dateEmission = new Date(document.getElementById('date_emission').value);
            const dateEcheance = new Date(document.getElementById('date_echeance').value);

            // Vérifier que la date d'émission est antérieure à la date d'échéance
            if (dateEmission > dateEcheance) {
                alert('La date d\'émission doit être antérieure à la date d\'échéance.');
                return false;
            }

            // Vérifier qu'un prestataire est sélectionné
            if (!document.getElementById('id_prestataire').value) {
                alert('Veuillez sélectionner un prestataire.');
                return false;
            }

            // Vérifier qu'un devis est sélectionné
            if (!document.getElementById('id_devis').value) {
                alert('Veuillez sélectionner un devis.');
                return false;
            }

            // Si le statut est "Payee", vérifier qu'une méthode de paiement est sélectionnée
            if (document.getElementById('statut').value === 'Payee' && !document.getElementById('methode_paiement').value) {
                alert('Veuillez sélectionner une méthode de paiement pour une facture payée.');
                return false;
            }

            return true;
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.classList.remove('d-none');
            document.getElementById('loadingMessage').classList.add('d-none');
        }

        function formatDateForInput(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }
    </script>
</body>
</html>
