<?php
$title = "Gestion des événements";
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
                    <h1 class="h2">Gestion des événements</h1>
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
                            <i class="fas fa-plus"></i> Nouvel événement
                        </button>
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3>142</h3>
                            <p class="text-muted mb-0">Événements planifiés</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +12% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>3,256</h3>
                            <p class="text-muted mb-0">Participants</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +15% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>28</h3>
                            <p class="text-muted mb-0">À venir (30 jours)</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +8% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>92%</h3>
                            <p class="text-muted mb-0">Taux de participation</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +5% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event List Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Liste des événements</h5>
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
                                            <li><a class="dropdown-item" href="#">Tous les événements</a></li>
                                            <li><a class="dropdown-item" href="#">À venir</a></li>
                                            <li><a class="dropdown-item" href="#">En cours</a></li>
                                            <li><a class="dropdown-item" href="#">Terminés</a></li>
                                            <li><a class="dropdown-item" href="#">Annulés</a></li>
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
                                                <th>Événement</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Lieu</th>
                                                <th>Participants</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>E-2025-0145</td>
                                                <td>Conférence Business Tech</td>
                                                <td>Conférence</td>
                                                <td>15/04/2025</td>
                                                <td>Paris</td>
                                                <td>250/300</td>
                                                <td><span class="badge bg-primary">À venir</span></td>
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
                                                <td>E-2025-0144</td>
                                                <td>Séminaire Développement Durable</td>
                                                <td>Séminaire</td>
                                                <td>02/04/2025</td>
                                                <td>Lyon</td>
                                                <td>125/150</td>
                                                <td><span class="badge bg-primary">À venir</span></td>
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
                                                <td>E-2025-0143</td>
                                                <td>Salon de l'Innovation</td>
                                                <td>Salon</td>
                                                <td>20/03/2025 - 22/03/2025</td>
                                                <td>Marseille</td>
                                                <td>850/1000</td>
                                                <td><span class="badge bg-success">En cours</span></td>
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
                                                <td>E-2025-0142</td>
                                                <td>Workshop Intelligence Artificielle</td>
                                                <td>Workshop</td>
                                                <td>15/03/2025</td>
                                                <td>Bordeaux</td>
                                                <td>45/50</td>
                                                <td><span class="badge bg-secondary">Terminé</span></td>
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
                                                <td>E-2025-0141</td>
                                                <td>Journée Portes Ouvertes</td>
                                                <td>JPO</td>
                                                <td>10/03/2025</td>
                                                <td>Lille</td>
                                                <td>320/300</td>
                                                <td><span class="badge bg-secondary">Terminé</span></td>
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
                    <!-- Event Types Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par type d'événement</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="eventTypeChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Participation Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Participation aux événements (par mois)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="participationChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Événements à venir (prochains 30 jours)</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Événement</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Lieu</th>
                                                <th>Inscrits</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Forum des Métiers du Numérique</td>
                                                <td>Forum</td>
                                                <td>25/03/2025</td>
                                                <td>Toulouse</td>
                                                <td>180/200</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Gérer</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Conférence Cybersécurité</td>
                                                <td>Conférence</td>
                                                <td>28/03/2025</td>
                                                <td>Paris</td>
                                                <td>145/200</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Gérer</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Atelier Design Thinking</td>
                                                <td>Workshop</td>
                                                <td>02/04/2025</td>
                                                <td>Nantes</td>
                                                <td>25/30</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Gérer</button>
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
                            <small class="text-muted">Dernière mise à jour: 21/03/2025 14:30</small>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>


    <!-- Scripts pour les graphiques -->
    <script>
        // Event Type Chart
        const typeCtx = document.getElementById('eventTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Conférence', 'Séminaire', 'Workshop', 'Salon', 'Webinaire', 'JPO'],
                datasets: [{
                    data: [35, 20, 15, 12, 10, 8],
                    backgroundColor: [
                        '#3a86ff',
                        '#8338ec',
                        '#ff006e',
                        '#fb5607',
                        '#ffbe0b',
                        '#06d6a0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Participation Chart
        const participationCtx = document.getElementById('participationChart').getContext('2d');
        const participationChart = new Chart(participationCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Nombre de participants',
                    data: [280, 320, 420, 380, 450, 520, 350, 250, 480, 550, 620, 580],
                    backgroundColor: 'rgba(58, 134, 255, 0.7)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
