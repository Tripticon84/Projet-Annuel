<?php
$title = "Gestion des contrats";
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
                    <h1 class="h2">Gestion des contrats</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown me-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-calendar"></i> Cette année
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">Ce mois</a></li>
                                <li><a class="dropdown-item" href="#">Ce trimestre</a></li>
                                <li><a class="dropdown-item" href="#">Cette année</a></li>
                                <li><a class="dropdown-item" href="#">Tous</a></li>
                            </ul>
                        </div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Exporter</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Imprimer</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nouveau contrat
                        </button>
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <h3>256</h3>
                            <p class="text-muted mb-0">Contrats actifs</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +8% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <h3>875K€</h3>
                            <p class="text-muted mb-0">Valeur totale</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +12% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <h3>32</h3>
                            <p class="text-muted mb-0">En négociation</p>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-arrow-down"></i> -5% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>18</h3>
                            <p class="text-muted mb-0">Expirant bientôt</p>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-arrow-up"></i> +25% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract List Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Liste des contrats</h5>
                                <div class="d-flex">
                                    <div class="input-group me-2">
                                        <input type="text" class="form-control form-control-sm" placeholder="Rechercher...">
                                        <button class="btn btn-sm btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Filtrer par
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                            <li><a class="dropdown-item" href="#">Tous les contrats</a></li>
                                            <li><a class="dropdown-item" href="#">Contrats actifs</a></li>
                                            <li><a class="dropdown-item" href="#">En négociation</a></li>
                                            <li><a class="dropdown-item" href="#">Expirant bientôt</a></li>
                                            <li><a class="dropdown-item" href="#">Expirés</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="form-check-input"></th>
                                                <th>Référence</th>
                                                <th>Client</th>
                                                <th>Type</th>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                                <th>Valeur (€)</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>C-2025-0452</td>
                                                <td>TechInnov</td>
                                                <td>Premium</td>
                                                <td>02/03/2025</td>
                                                <td>01/03/2026</td>
                                                <td>45,000</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>C-2025-0451</td>
                                                <td>EcoSolutions</td>
                                                <td>Basic</td>
                                                <td>01/03/2025</td>
                                                <td>28/02/2026</td>
                                                <td>28,500</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>C-2025-0450</td>
                                                <td>DigitalWave</td>
                                                <td>Basic</td>
                                                <td>En attente</td>
                                                <td>En attente</td>
                                                <td>22,500</td>
                                                <td><span class="badge bg-warning">En négociation</span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>C-2025-0449</td>
                                                <td>GreenLife</td>
                                                <td>Starter</td>
                                                <td>25/02/2025</td>
                                                <td>24/02/2026</td>
                                                <td>5,400</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>C-2025-0448</td>
                                                <td>SmartRetail</td>
                                                <td>Premium</td>
                                                <td>20/02/2025</td>
                                                <td>19/02/2026</td>
                                                <td>87,500</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mt-4">
                    <!-- Contract Types Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par type de contrat</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="contractTypeChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Value Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Valeur des contrats (par mois)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="contractValueChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiring Contracts Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Contrats expirant prochainement</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Type</th>
                                                <th>Date d'expiration</th>
                                                <th>Valeur</th>
                                                <th>Statut de renouvellement</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>FutureTech</td>
                                                <td>Premium</td>
                                                <td>15/03/2025</td>
                                                <td>62,500 €</td>
                                                <td><span class="badge bg-warning">En discussion</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Contacter</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>BioHealth</td>
                                                <td>Basic</td>
                                                <td>22/03/2025</td>
                                                <td>24,800 €</td>
                                                <td><span class="badge bg-danger">Non démarré</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Contacter</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>GlobalFinance</td>
                                                <td>Premium</td>
                                                <td>01/04/2025</td>
                                                <td>78,900 €</td>
                                                <td><span class="badge bg-success">Confirmé</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-success">Préparer</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="pt-4 my-4 border-top">
                    <div class="row">
                        <div class="col-12 col-md text-center text-md-start">
                            <p>&copy; 2025 Business Care - Administration</p>
                            <small class="d-block text-muted">Version 3.5.2</small>
                        </div>
                        <div class="col-6 col-md text-end">
                            <small class="text-muted">Dernière mise à jour: 03/03/2025 10:45</small>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Scripts pour les graphiques -->
    <script>
        // Contract Type Chart
        const typeCtx = document.getElementById('contractTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Premium', 'Basic', 'Starter'],
                datasets: [{
                    data: [45, 35, 20],
                    backgroundColor: [
                        '#3a86ff',
                        '#8338ec',
                        '#ff006e'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Contract Value Chart
        const valueCtx = document.getElementById('contractValueChart').getContext('2d');
        const valueChart = new Chart(valueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Valeur des nouveaux contrats (K€)',
                    data: [65, 78, 92, 68, 72, 85, 73, 80, 95, 88, 105, 115],
                    backgroundColor: 'rgba(58, 134, 255, 0.7)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' K€';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
