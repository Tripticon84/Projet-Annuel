<?php
$title = "Gestion des Employés";
include_once "../includes/head.php"
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include_once "../includes/sidebar.php" ?>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestion des Employés</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>3,482</h3>
                            <p class="text-muted mb-0">Employés inscrits</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +8% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h3>2,940</h3>
                            <p class="text-muted mb-0">Actifs ce mois</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +5% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>128</h3>
                            <p class="text-muted mb-0">Nouveaux ce mois</p>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-arrow-down"></i> -12% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <h3>84%</h3>
                            <p class="text-muted mb-0">Taux de participation</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +7% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Employee Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">Liste des Employés</h5>
                        <div class="d-flex flex-wrap mt-2 mt-sm-0  align-items-center">
                            <div class="input-group me-2 mb-2 mb-sm-0" style="max-width: 210px;">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Rechercher un employé..." aria-label="Search">
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <a type="button" class="btn btn-sm btn-primary me-2" href="create.php">
                                <i class="fas fa-plus"></i> Nouvel Employé
                            </a>
                            <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filtre
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li>
                                        <h6 class="dropdown-header">Entreprise</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Toutes les entreprises</a></li>
                                    <li><a class="dropdown-item" href="#">TechInnov</a></li>
                                    <li><a class="dropdown-item" href="#">EcoSolutions</a></li>
                                    <li><a class="dropdown-item" href="#">DigitalWave</a></li>
                                    <li><a class="dropdown-item" href="#">SmartRetail</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header">Statut</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Tous les statuts</a></li>
                                    <li><a class="dropdown-item" href="#">Actifs</a></li>
                                    <li><a class="dropdown-item" href="#">Inactifs</a></li>
                                    <li><a class="dropdown-item" href="#">En congé</a></li>
                                </ul>
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort"></i> Tri
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#">Nom (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Nom (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#">Date d'inscription (récent)</a></li>
                                    <li><a class="dropdown-item" href="#">Date d'inscription (ancien)</a></li>
                                    <li><a class="dropdown-item" href="#">Participation (élevée-basse)</a></li>
                                    <li><a class="dropdown-item" href="#">Participation (basse-élevée)</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Exporter
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nom/Prénom</th>
                                        <th scope="col">Identifiant</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Rôle</th>
                                        <th scope="col">Téléphone</th>
                                        <th scope="col">Entreprise</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeList">
                                    <!-- Les employés seront insérés ici par JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small" id="paginationInfo">Chargement des données...</span>
                        </div>
                        <nav aria-label="Table navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">«</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">»</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mt-4">
                    <!-- Employee Distribution By Department -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par service</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="departmentChart" height="260"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Trend -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Tendance de participation</h5>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary active">Semaine</button>
                                    <button type="button" class="btn btn-outline-secondary">Mois</button>
                                    <button type="button" class="btn btn-outline-secondary">Année</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="activityChart" height="260"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third Row -->
                <div class="row mt-4">
                    <!-- Top Active Employees -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Employés les plus actifs</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Sophie Martin</h6>
                                                <p class="mb-0 small text-muted">Marketing | TechInnov</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="width: 80px; height: 5px;">
                                                        <div class="progress-bar bg-success" style="width: 92%"></div>
                                                    </div>
                                                    <span>92%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Julie Bernard</h6>
                                                <p class="mb-0 small text-muted">RH | EcoSolutions</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="width: 80px; height: 5px;">
                                                        <div class="progress-bar bg-success" style="width: 90%"></div>
                                                    </div>
                                                    <span>90%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Thomas Leroy</h6>
                                                <p class="mb-0 small text-muted">Développeur | TechInnov</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="width: 80px; height: 5px;">
                                                        <div class="progress-bar bg-success" style="width: 87%"></div>
                                                    </div>
                                                    <span>87%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Laura Simon</h6>
                                                <p class="mb-0 small text-muted">Commercial | EcoSolutions</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="width: 80px; height: 5px;">
                                                        <div class="progress-bar bg-success" style="width: 88%"></div>
                                                    </div>
                                                    <span>88%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Emma Petit</h6>
                                                <p class="mb-0 small text-muted">Marketing | SmartRetail</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="width: 80px; height: 5px;">
                                                        <div class="progress-bar bg-success" style="width: 82%"></div>
                                                    </div>
                                                    <span>82%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-sm btn-primary">Voir tous les employés actifs</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Employee Activities -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Activités récentes des employés</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2">Sophie Martin</h6>
                                                    <span class="badge bg-success">Connexion</span>
                                                </div>
                                                <p class="mb-0 small text-muted">Il y a 10 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2">Thomas Leroy</h6>
                                                    <span class="badge bg-info">Modification profil</span>
                                                </div>
                                                <p class="mb-0 small text-muted">Il y a 25 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2">Julie Bernard</h6>
                                                    <span class="badge bg-primary">Inscription événement</span>
                                                </div>
                                                <p class="mb-0 small text-muted">Il y a 45 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2">Marc Dubois</h6>
                                                    <span class="badge bg-warning text-dark">Déconnexion</span>
                                                </div>
                                                <p class="mb-0 small text-muted">Il y a 1 heure</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2">Emma Petit</h6>
                                                    <span class="badge bg-success">Document téléchargé</span>
                                                </div>
                                                <p class="mb-0 small text-muted">Il y a 2 heures</p>
                                            </div>
                                        </div>
                                    </div>
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="#" class="btn btn-sm btn-primary">Voir toutes les activités</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Row -->
                    <div class="row mt-4">
                        <!-- Employee Status Stats -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statut des employés par entreprise</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Engagement -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Participation aux activités</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>TechInnov</span>
                                            <span>83%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 83%" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>EcoSolutions</span>
                                            <span>78%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>DigitalWave</span>
                                            <span>67%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>SmartRetail</span>
                                            <span>74%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 74%" aria-valuenow="74" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>GreenLife</span>
                                            <span>62%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Action Cards -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Actions rapides</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3 mb-3">
                                <div class="mb-3">
                                    <i class="fas fa-user-plus fa-2x text-primary"></i>
                                </div>
                                <h6>Nouvel employé</h6>
                                <a href="#" class="btn btn-sm btn-outline-primary mt-2">Ajouter</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3 mb-3">
                                <div class="mb-3">
                                    <i class="fas fa-file-export fa-2x text-success"></i>
                                </div>
                                <h6>Exporter données</h6>
                                <a href="#" class="btn btn-sm btn-outline-success mt-2">Exporter</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3 mb-3">
                                <div class="mb-3">
                                    <i class="fas fa-envelope fa-2x text-warning"></i>
                                </div>
                                <h6>Envoyer message groupé</h6>
                                <a href="#" class="btn btn-sm btn-outline-warning mt-2">Composer</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3 mb-3">
                                <div class="mb-3">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
                                </div>
                                <h6>Rapport de performance</h6>
                                <a href="#" class="btn btn-sm btn-outline-info mt-2">Générer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Fetch Employee List
        document.addEventListener('DOMContentLoaded', function() {
            fetchEmployees();

            // Recherche par l'input de recherche
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    fetchEmployees(this.value);
                }
            });
        });

        function fetchEmployees(username = '') {
            const employeeList = document.getElementById('employeeList');
            employeeList.innerHTML = '<tr><td colspan="8" class="text-center">Chargement des employés...</td></tr>';

            let url = '../../api/employee/getAll.php';
            if (username) {
                url += `?username=${encodeURIComponent(username)}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la récupération des employés');
                    }
                    return response.json();
                })
                .then(data => {
                    employeeList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(employee => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${employee.collaborateur_id}</td>
                                <td>
                                    <h6 class="mb-0">${employee.nom} ${employee.prenom}</h6>
                                </td>
                                <td>${employee.username}</td>
                                <td>${employee.email || '-'}</td>
                                <td>${employee.role || '-'}</td>
                                <td>${employee.telephone || '-'}</td>
                                <td>${employee.id_societe || '-'}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                            <li><a class="dropdown-item" href="modify.php?id=${employee.collaborateur_id}"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteEmployee(${employee.collaborateur_id}); return false;"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                        </ul>
                                    </div>
                                </td>
                            `;
                            employeeList.appendChild(row);
                        });

                        document.getElementById('paginationInfo').textContent = `Affichage de 1-${data.length} sur ${data.length} employés`;
                    } else {
                        employeeList.innerHTML = '<tr><td colspan="8" class="text-center">Aucun employé trouvé</td></tr>';
                        document.getElementById('paginationInfo').textContent = 'Aucun employé trouvé';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    employeeList.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des données</td></tr>';
                    document.getElementById('paginationInfo').textContent = 'Erreur lors du chargement des données';
                });
        }

        function deleteEmployee(employeeId) {
            if (confirm('Êtes-vous sûr de vouloir désactiver cet employé?')) {
                fetch('../../api/employee/delete.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: employeeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || (data.message && data.message.includes('supprimé'))) {
                        alert('Employé désactivé avec succès.');
                        fetchEmployees(); // Rafraîchir la liste
                    } else {
                        alert('Erreur lors de la désactivation. Veuillez réessayer.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la désactivation.');
                });
            }
        }
    </script>

    <!-- Scripts pour les graphiques -->
    <script>
        // Graphique de répartition par service
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentChart = new Chart(departmentCtx, {
            type: 'pie',
            data: {
                labels: ['Marketing', 'R&D', 'Commercial', 'RH', 'Direction', 'Finance', 'IT'],
                datasets: [{
                    data: [25, 18, 22, 14, 5, 8, 8],
                    backgroundColor: [
                        '#3a86ff',
                        '#8338ec',
                        '#ff006e',
                        '#fb5607',
                        '#ffbe0b',
                        '#38b000',
                        '#14213d'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Graphique de tendance de participation
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                        label: 'Cette semaine',
                        data: [78, 82, 79, 85, 83, 45, 38],
                        borderColor: '#3a86ff',
                        backgroundColor: 'rgba(58, 134, 255, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Semaine précédente',
                        data: [72, 75, 78, 74, 80, 42, 35],
                        borderColor: '#8338ec',
                        backgroundColor: 'rgba(131, 56, 236, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // Graphique de statut des employés
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['TechInnov', 'EcoSolutions', 'DigitalWave', 'SmartRetail', 'GreenLife'],
                datasets: [{
                        label: 'Actifs',
                        data: [850, 620, 480, 390, 220],
                        backgroundColor: '#4CAF50'
                    },
                    {
                        label: 'En congé',
                        data: [120, 85, 65, 50, 35],
                        backgroundColor: '#FFC107'
                    },
                    {
                        label: 'Inactifs',
                        data: [30, 45, 55, 40, 25],
                        backgroundColor: '#F44336'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
