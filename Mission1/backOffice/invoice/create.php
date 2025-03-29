<?php
$title = "Créer une facture";
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
                    <h1 class="h2">Créer une facture</h1>
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
                        <form id="createInvoiceForm">
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
                                <button type="reset" class="btn btn-outline-secondary">Réinitialiser</button>
                                <button type="submit" class="btn btn-primary">Créer la facture</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Définir la date d'émission par défaut à aujourd'hui
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_emission').value = today;

            // Définir la date d'échéance par défaut à 30 jours à partir d'aujourd'hui
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.getElementById('date_echeance').value = dueDate.toISOString().split('T')[0];

            // Charger la liste des prestataires
            fetchProviders();

            // Ajouter un écouteur d'événements pour charger les devis lorsqu'un prestataire est sélectionné
            document.getElementById('id_prestataire').addEventListener('change', function() {
                const providerId = this.value;
                if (providerId) {
                    fetchEstimates(providerId);
                } else {
                    // Réinitialiser la liste des devis si aucun prestataire n'est sélectionné
                    const estimateSelect = document.getElementById('id_devis');
                    estimateSelect.innerHTML = '<option value="">Sélectionner un devis</option>';
                    // Réinitialiser les montants
                    document.getElementById('montant_ht').value = '';
                    document.getElementById('montant_tva').value = '';
                    document.getElementById('montant').value = '';
                }
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

            // Soumission du formulaire
            const form = document.getElementById('createInvoiceForm');
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

                fetch('../../api/invoice/create.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + getToken()
                    },
                    body: JSON.stringify(invoiceData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        alert('Facture créée avec succès!');
                        window.location.href = 'invoice.php';
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible de créer la facture'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la création de la facture.');
                });
            });
        });

        function fetchProviders() {
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
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des prestataires:', error);
            });
        }

        function fetchEstimates(providerId) {
            fetch(`../../api/estimate/getByProvider.php?id_prestataire=${providerId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                const estimateSelect = document.getElementById('id_devis');
                estimateSelect.innerHTML = '<option value="">Sélectionner un devis</option>';

                if (data && data.length > 0) {
                    // Filtrer uniquement les devis acceptés (contrats)
                    const acceptedEstimates = data.filter(estimate => estimate.is_contract === 1);

                    if (acceptedEstimates.length > 0) {
                        acceptedEstimates.forEach(estimate => {
                            const option = document.createElement('option');
                            option.value = estimate.devis_id;
                            option.textContent = `Devis #${estimate.devis_id} - ${estimate.montant} €`;
                            estimateSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "Aucun devis accepté disponible";
                        option.disabled = true;
                        estimateSelect.appendChild(option);
                    }
                } else {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "Aucun devis disponible";
                    option.disabled = true;
                    estimateSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des devis:', error);
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
    </script>
</body>
</html>
