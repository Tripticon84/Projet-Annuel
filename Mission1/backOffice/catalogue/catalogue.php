<?php
$title = "Catalogue";
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
                    <h1 class="h2">Catalogue de services</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-plus"></i> Ajouter un service
                        </button>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-file-export"></i> Exporter
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print"></i> Imprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-store"></i>
                            </div>
                            <h3>245</h3>
                            <p class="text-muted mb-0">Services actifs</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +12% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>53.8K€</h3>
                            <p class="text-muted mb-0">CA mensuel du catalogue</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +7% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3>4.7</h3>
                            <p class="text-muted mb-0">Note moyenne</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +0.2 depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3>185</h3>
                            <p class="text-muted mb-0">Réservations ce mois</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +15% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Filtres et recherche</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control" placeholder="Rechercher un service...">
                                            <button class="btn btn-outline-secondary" type="button">Rechercher</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select">
                                            <option selected>Catégorie</option>
                                            <option>Bien-être mental</option>
                                            <option>Activité physique</option>
                                            <option>Nutrition</option>
                                            <option>Conférences</option>
                                            <option>Team building</option>
                                            <option>Développement personnel</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select">
                                            <option selected>Tri par</option>
                                            <option>Popularité</option>
                                            <option>Prix croissant</option>
                                            <option>Prix décroissant</option>
                                            <option>Mieux notés</option>
                                            <option>Récemment ajoutés</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <select class="form-select">
                                            <option selected>Format</option>
                                            <option>Présentiel</option>
                                            <option>Distanciel</option>
                                            <option>Hybride</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <select class="form-select">
                                            <option selected>Durée</option>
                                            <option>Moins de 1h</option>
                                            <option>1-2 heures</option>
                                            <option>Demi-journée</option>
                                            <option>Journée complète</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <select class="form-select">
                                            <option selected>Niveau de prix</option>
                                            <option>Économique</option>
                                            <option>Intermédiaire</option>
                                            <option>Premium</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Prix</span>
                                            <input type="number" class="form-control" placeholder="Min">
                                            <input type="number" class="form-control" placeholder="Max">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-2">
                                        <div class="d-grid">
                                            <button class="btn btn-primary" type="button">Appliquer les filtres</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Navigation -->
                <div class="row mt-4">
                    <div class="col-12">
                        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">Tous</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-mental-tab" data-bs-toggle="pill" data-bs-target="#pills-mental" type="button" role="tab" aria-controls="pills-mental" aria-selected="false">Bien-être mental</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-physical-tab" data-bs-toggle="pill" data-bs-target="#pills-physical" type="button" role="tab" aria-controls="pills-physical" aria-selected="false">Activité physique</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-nutrition-tab" data-bs-toggle="pill" data-bs-target="#pills-nutrition" type="button" role="tab" aria-controls="pills-nutrition" aria-selected="false">Nutrition</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-conference-tab" data-bs-toggle="pill" data-bs-target="#pills-conference" type="button" role="tab" aria-controls="pills-conference" aria-selected="false">Conférences</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-team-tab" data-bs-toggle="pill" data-bs-target="#pills-team" type="button" role="tab" aria-controls="pills-team" aria-selected="false">Team building</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Catalog Items -->
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                                <div class="row">
                                    <!-- Item 1 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Atelier+Méditation" class="card-img-top" alt="Atelier méditation">
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2">Populaire</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-info">Bien-être mental</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.9 (128)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Atelier de méditation pleine conscience</h5>
                                                <p class="card-text text-muted small">Initiation à la méditation pleine conscience pour réduire le stress et améliorer la concentration.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">60 min</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">5-20 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">450 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item 2 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Yoga+Corporate" class="card-img-top" alt="Yoga corporate">
                                                <span class="badge bg-primary position-absolute top-0 start-0 m-2">Best-seller</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-warning text-dark">Activité physique</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.8 (95)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Yoga corporate</h5>
                                                <p class="card-text text-muted small">Séance de yoga adaptée au milieu professionnel, accessible à tous les niveaux.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">45 min</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">5-15 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">350 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item 3 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Conférence+Stress" class="card-img-top" alt="Conférence gestion du stress">
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-danger">Conférence</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.7 (72)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Conférence gestion du stress</h5>
                                                <p class="card-text text-muted small">Conférence sur les mécanismes du stress et les techniques concrètes pour mieux le gérer.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">90 min</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">10-100 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">850 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item 4 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Nutrition+Workshop" class="card-img-top" alt="Atelier nutrition">
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-success">Nutrition</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.6 (54)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Atelier nutrition & énergie</h5>
                                                <p class="card-text text-muted small">Découvrez comment l'alimentation influence votre niveau d'énergie et votre productivité.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">120 min</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">8-20 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">750 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item 5 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Team+Building" class="card-img-top" alt="Team building écologique">
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2">Nouveau</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-secondary">Team building</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.9 (18)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Team building écologique</h5>
                                                <p class="card-text text-muted small">Activité de cohésion d'équipe axée sur l'environnement et le développement durable.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">3-4h</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">10-50 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">1450 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item 6 -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="position-relative">
                                                <img src="https://via.placeholder.com/300x180?text=Consultation+Psy" class="card-img-top" alt="Consultation psychologue">
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-info">Bien-être mental</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <span class="ms-1">4.8 (65)</span>
                                                    </div>
                                                </div>
                                                <h5 class="card-title">Consultation psychologue</h5>
                                                <p class="card-text text-muted small">Séance individuelle avec un psychologue spécialisé en milieu professionnel.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        <small class="text-muted">50 min</small>
                                                        <i class="fas fa-users ms-2 me-1 text-muted"></i>
                                                        <small class="text-muted">1 pers.</small>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">120 €</h6>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white d-flex justify-content-between">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-pen"></i> Modifier
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Suivant</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                            <!-- Other tabs content would go here -->
                            <div class="tab-pane fade" id="pills-mental" role="tabpanel" aria-labelledby="pills-mental-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Filtrage par catégorie "Bien-être mental" - 42 services disponibles
                                </div>
                                <!-- Similar content structure to the "all" tab but filtered -->
                            </div>

                            <!-- Placeholders for other category tabs -->
                            <div class="tab-pane fade" id="pills-physical" role="tabpanel" aria-labelledby="pills-physical-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Filtrage par catégorie "Activité physique" - 38 services disponibles
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-nutrition" role="tabpanel" aria-labelledby="pills-nutrition-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Filtrage par catégorie "Nutrition" - 27 services disponibles
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-conference" role="tabpanel" aria-labelledby="pills-conference-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Filtrage par catégorie "Conférences" - 35 services disponibles
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-team" role="tabpanel" aria-labelledby="pills-team-tab">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Filtrage par catégorie "Team building" - 22 services disponibles
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par catégorie</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title">Popularité des services</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="popularityChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Scripts pour les graphiques -->
    <script>
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bien-être mental', 'Activité physique', 'Nutrition', 'Conférences', 'Team building', 'Développement personnel'],
                datasets: [{
                    data: [42, 38, 27, 35, 22, 18],
                    backgroundColor: [
                        '#3a86ff',
                        '#ff006e',
                        '#8ac926',
                        '#fb5607',
                        '#8338ec',
                        '#ffbe0b'
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

        // Popularity Chart
        const popularityCtx = document.getElementById('popularityChart').getContext('2d');
        const popularityChart = new Chart(popularityCtx, {
            type: 'bar',
            data: {
                labels: ['Méditation', 'Yoga', 'Gestion stress', 'Nutrition', 'Team building', 'Consultation psy'],
                datasets: [{
                    label: 'Réservations mensuelles',
                    data: [42, 38, 35, 28, 25, 17],
                    backgroundColor: [
                        'rgba(58, 134, 255, 0.7)',
                        'rgba(255, 0, 110, 0.7)',
                        'rgba(138, 201, 38, 0.7)',
                        'rgba(251, 86, 7, 0.7)',
                        'rgba(131, 56, 236, 0.7)',
                        'rgba(255, 190, 11, 0.7)'
                    ],
                    borderColor: [
                        '#3a86ff',
                        '#ff006e',
                        '#8ac926',
                        '#fb5607',
                        '#8338ec',
                        '#ffbe0b'
                    ],
                    borderWidth: 1
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
