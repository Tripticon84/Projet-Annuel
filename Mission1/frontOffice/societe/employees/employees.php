<?php
$title = "Gestion des Collaborateurs";

include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/head.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Inclusion de la sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des Collaborateurs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshData">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            <i class="fas fa-plus"></i> Nouveau collaborateur
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
                    <form id="employeeFilterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="nameFilter" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nameFilter" placeholder="Rechercher par nom">
                        </div>
                        <div class="col-md-3">
                            <label for="roleFilter" class="form-label">Rôle</label>
                            <select id="roleFilter" class="form-select">
                                <option value="">Tous</option>
                                <option value="employe">Employé</option>
                                <option value="manager">Manager</option>
                                <option value="responsable_rh">Responsable RH</option>
                                <option value="directeur">Directeur</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateFilter" class="form-label">Date d'ajout</label>
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="applyFilters" class="btn btn-primary">Appliquer</button>
                            <button type="button" id="resetFilters" class="btn btn-secondary ms-2">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des collaborateurs -->
            <div class="card">
                <div class="card-header">
                    <h5>Liste des collaborateurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Rôle</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Date d'ajout</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employees-table">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement des collaborateurs...</span>
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

<!-- Modal pour ajouter un collaborateur -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Ajouter un collaborateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="employe">Employé</option>
                                <option value="manager">Manager</option>
                                <option value="responsable_rh">Responsable RH</option>
                                <option value="directeur">Directeur</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Le mot de passe doit comporter au moins 8 caractères.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveEmployee">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier un collaborateur -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Modifier un collaborateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm">
                    <input type="hidden" id="edit_employee_id" name="id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="edit_nom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="edit_prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_role" class="form-label">Rôle</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="employe">Employé</option>
                                <option value="manager">Manager</option>
                                <option value="responsable_rh">Responsable RH</option>
                                <option value="directeur">Directeur</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="edit_telephone" name="telephone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nouveau mot de passe (optionnel)</label>
                        <input type="password" class="form-control" id="edit_password" name="password" minlength="8">
                        <div class="form-text">Laissez vide pour conserver le mot de passe actuel.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateEmployee">Enregistrer les modifications</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails d'un collaborateur -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeeModalLabel">Détails du collaborateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="employee-details">
                <!-- Le contenu sera injecté dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" id="editEmployee">Modifier</button>
                <button type="button" class="btn btn-danger" id="deactivateEmployee">Désactiver</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let societyId = <?php echo $_SESSION['societe_id']; ?>;

    // Fonction d'initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Charger tous les collaborateurs
        loadEmployees(societyId);

        // Configuration des événements
        document.getElementById('refreshData').addEventListener('click', function() {
            loadEmployees(societyId);
        });

        document.getElementById('applyFilters').addEventListener('click', function() {
            applyEmployeeFilters();
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('employeeFilterForm').reset();
            loadEmployees(societyId);
        });

        document.getElementById('saveEmployee').addEventListener('click', function() {
            addEmployee(societyId);
        });

        document.getElementById('updateEmployee').addEventListener('click', function() {
            updateEmployee();
        });

        // Événement pour le bouton de désactivation
        document.getElementById('desactivateEmployee').addEventListener('click', function() {
            const employeeId = this.getAttribute('data-id');
            desactivateEmployee(employeeId);
        });

        // Événement pour le bouton d'édition dans la vue détails
        document.getElementById('editEmployee').addEventListener('click', function() {
            const employeeId = this.getAttribute('data-id');
            const employee = this.getAttribute('data-employee');
            if (employee) {
                const employeeData = JSON.parse(employee);
                openEditModal(employeeData);
            } else {
                // Si les données ne sont pas disponibles, charger depuis l'API
                fetchEmployeeDetails(employeeId);
            }
        });

        // Configurer les événements pour générer automatiquement le nom d'utilisateur
        document.getElementById('nom').addEventListener('input', updateUsername);
        document.getElementById('prenom').addEventListener('input', updateUsername);
    });

    // Générer un nom d'utilisateur à partir du prénom et du nom
    function generateUsername(prenom, nom) {
        if (!prenom || !nom) return '';

        // Prendre la première lettre du prénom et la concaténer avec le nom
        const firstLetter = prenom.charAt(0).toLowerCase();
        const lastName = nom.toLowerCase();

        // Nettoyer les caractères spéciaux et les espaces
        const cleanLastName = lastName.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "") // Supprimer les accents
            .replace(/[^a-z0-9]/g, "");      // Supprimer les caractères spéciaux et espaces

        return firstLetter + cleanLastName;
    }

    // Mettre à jour le champ username lorsque le nom ou le prénom change
    function updateUsername() {
        const prenom = document.getElementById('prenom').value;
        const nom = document.getElementById('nom').value;

        if (prenom && nom) {
            const username = generateUsername(prenom, nom);
            document.getElementById('username').value = username;
        }
    }

    // Charger les collaborateurs avec filtres optionnels
    function loadEmployees(societyId, filters = {}) {
        // Construire l'URL avec les filtres
        let url = `/api/company/getAllEmployee.php?societe_id=${societyId}`;

        if (filters.name) url += `&name=${encodeURIComponent(filters.name)}`;
        if (filters.role) url += `&role=${encodeURIComponent(filters.role)}`;
        if (filters.date) url += `&date=${filters.date}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('employees-table');

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Aucun collaborateur trouvé</td></tr>';
                return;
            }

            tableBody.innerHTML = '';
            data.forEach(employee => {
                const dateCreation = employee.date_creation ? new Date(employee.date_creation).toLocaleDateString('fr-FR') : 'N/A';
                tableBody.innerHTML += `
                    <tr>
                        <td>${employee.collaborateur_id}</td>
                        <td>${employee.nom}</td>
                        <td>${employee.prenom}</td>
                        <td>${employee.username}</td>
                        <td>${employee.role}</td>
                        <td>${employee.email}</td>
                        <td>${employee.telephone}</td>
                        <td>${dateCreation}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewEmployeeDetails(${employee.collaborateur_id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="editEmployee(${employee.collaborateur_id}, '${employee.nom}', '${employee.prenom}', '${employee.username}', '${employee.role}', '${employee.email}', '${employee.telephone}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="confirmDeactivateEmployee(${employee.collaborateur_id})">
                                <i class="fas fa-user-slash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des collaborateurs:', error);
            document.getElementById('employees-table').innerHTML =
                `<tr><td colspan="9" class="text-center text-danger">Erreur lors du chargement des collaborateurs</td></tr>`;
        });
    }

    // Appliquer les filtres
    function applyEmployeeFilters() {
        const filters = {
            name: document.getElementById('nameFilter').value,
            role: document.getElementById('roleFilter').value,
            date: document.getElementById('dateFilter').value
        };

        loadEmployees(societyId, filters);
    }

    // Afficher les détails d'un collaborateur
    function viewEmployeeDetails(id) {
        fetch(`/api/employee/getOne.php?id=${id}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(employee => {
            const detailsContainer = document.getElementById('employee-details');
            const dateCreation = employee.date_creation ? new Date(employee.date_creation).toLocaleDateString('fr-FR') : 'N/A';
            const dateActivite = employee.date_activite ? new Date(employee.date_activite).toLocaleDateString('fr-FR') : 'N/A';

            detailsContainer.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> ${employee.collaborateur_id}</p>
                        <p><strong>Nom:</strong> ${employee.nom}</p>
                        <p><strong>Prénom:</strong> ${employee.prenom}</p>
                        <p><strong>Nom d'utilisateur:</strong> ${employee.username}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Rôle:</strong> ${employee.role}</p>
                        <p><strong>Email:</strong> ${employee.email}</p>
                        <p><strong>Téléphone:</strong> ${employee.telephone}</p>
                        <p><strong>Date d'ajout:</strong> ${dateCreation}</p>
                        <p><strong>Dernière activité:</strong> ${dateActivite}</p>
                    </div>
                </div>
            `;

            // Stocker l'ID pour les actions
            document.getElementById('editEmployee').setAttribute('data-id', employee.collaborateur_id);
            document.getElementById('deactivateEmployee').setAttribute('data-id', employee.collaborateur_id);

            // Stocker les données de l'employé pour l'édition rapide
            document.getElementById('editEmployee').setAttribute('data-employee', JSON.stringify(employee));

            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur lors du chargement des détails du collaborateur:', error);
            alert('Erreur lors du chargement des détails du collaborateur');
        });
    }

    // Fonction pour ouvrir le modal d'édition avec les données pré-remplies
    function editEmployee(id, nom, prenom, username, role, email, telephone) {
        document.getElementById('edit_employee_id').value = id;
        document.getElementById('edit_nom').value = nom;
        document.getElementById('edit_prenom').value = prenom;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_telephone').value = telephone;
        document.getElementById('edit_password').value = '';

        // Fermer le modal de détails s'il est ouvert
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewEmployeeModal'));
        if (viewModal) {
            viewModal.hide();
        }

        // Ouvrir le modal d'édition
        const editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        editModal.show();
    }

    // Fonction pour ouvrir le modal d'édition à partir des données JSON
    function openEditModal(employeeData) {
        document.getElementById('edit_employee_id').value = employeeData.collaborateur_id;
        document.getElementById('edit_nom').value = employeeData.nom;
        document.getElementById('edit_prenom').value = employeeData.prenom;
        document.getElementById('edit_username').value = employeeData.username;
        document.getElementById('edit_role').value = employeeData.role;
        document.getElementById('edit_email').value = employeeData.email;
        document.getElementById('edit_telephone').value = employeeData.telephone;
        document.getElementById('edit_password').value = '';

        // Fermer le modal de détails
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewEmployeeModal'));
        if (viewModal) {
            viewModal.hide();
        }

        // Ouvrir le modal d'édition
        const editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        editModal.show();
    }

    // Fonction pour charger les détails d'un employé via l'API
    function fetchEmployeeDetails(id) {
        fetch(`/api/employee/getOne.php?id=${id}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(employee => {
            openEditModal(employee);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des détails du collaborateur:', error);
            alert('Erreur lors du chargement des détails du collaborateur');
        });
    }

    // Ajouter un collaborateur
    function addEmployee(societyId) {
        const formData = {
            nom: document.getElementById('nom').value,
            prenom: document.getElementById('prenom').value,
            username: document.getElementById('username').value,
            role: document.getElementById('role').value,
            email: document.getElementById('email').value,
            telephone: document.getElementById('telephone').value,
            password: document.getElementById('password').value,
            id_societe: societyId
        };

        fetch('/api/employee/create.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + getToken()
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.id) {
                // Succès
                const modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
                modal.hide();

                // Réinitialiser le formulaire
                document.getElementById('addEmployeeForm').reset();

                // Recharger la liste des employés
                loadEmployees(societyId);

                // Afficher un message de succès
                alert('Collaborateur ajouté avec succès!');
            } else {
                // Erreur
                alert(`Erreur: ${data.message || 'Une erreur est survenue'}`);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout du collaborateur:', error);
            alert('Une erreur est survenue lors de l\'ajout du collaborateur');
        });
    }

    // Mettre à jour un collaborateur
    function updateEmployee() {
        const formData = {
            id: document.getElementById('edit_employee_id').value,
            nom: document.getElementById('edit_nom').value,
            prenom: document.getElementById('edit_prenom').value,
            username: document.getElementById('edit_username').value,
            role: document.getElementById('edit_role').value,
            email: document.getElementById('edit_email').value,
            telephone: document.getElementById('edit_telephone').value
        };

        // Ajouter le mot de passe uniquement s'il a été saisi
        const password = document.getElementById('edit_password').value;
        if (password) {
            formData.password = password;
        }

        fetch('/api/employee/modify.php', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + getToken()
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Succès
                const modal = bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal'));
                modal.hide();

                // Recharger la liste des employés
                loadEmployees(societyId);

                // Afficher un message de succès
                alert('Collaborateur modifié avec succès!');
            } else {
                // Erreur
                alert(`Erreur: ${data.message || 'Une erreur est survenue'}`);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la modification du collaborateur:', error);
            alert('Une erreur est survenue lors de la modification du collaborateur');
        });
    }

    // Demande de confirmation avant de désactiver un collaborateur
    function confirmDeactivateEmployee(id) {
        if (confirm('Êtes-vous sûr de vouloir désactiver ce collaborateur?')) {
            deactivateEmployee(id);
        }
    }

    // Désactiver un collaborateur
    function deactivateEmployee(id) {
        fetch('/api/employee/deactivate.php', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + getToken()
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermer le modal de détails s'il est ouvert
                const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewEmployeeModal'));
                if (viewModal) {
                    viewModal.hide();
                }

                // Recharger la liste des employés
                loadEmployees(societyId);

                // Afficher un message de succès
                alert('Collaborateur désactivé avec succès!');
            } else {
                // Erreur
                alert(`Erreur: ${data.message || 'Une erreur est survenue'}`);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la désactivation du collaborateur:', error);
            alert('Une erreur est survenue lors de la désactivation du collaborateur');
        });
    }
</script>
