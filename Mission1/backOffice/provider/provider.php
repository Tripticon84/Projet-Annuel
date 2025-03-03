<?php
$title = "Gestion des prestataires";
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
                    <h1 class="h2">Gestion des prestataires</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Exporter</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Imprimer</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nouveau prestataire
                        </button>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Rechercher un prestataire...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" aria-label="Filtrer par catégorie">
                                    <option selected>Toutes les catégories</option>
                                    <option>Santé mentale</option>
                                    <option>Sport & Bien-être</option>
                                    <option>Nutrition</option>
                                    <option>Conférenciers</option>
                                    <option>Coach professionnel</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" aria-label="Filtrer par statut">
                                    <option selected>Tous les statuts</option>
                                    <option>Actif</option>
                                    <option>Inactif</option>
                                    <option>En attente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary w-100" type="button">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3>89</h3>
                            <p class="text-muted mb-0">Prestataires actifs</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +10% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3>435</h3>
                            <p class="text-muted mb-0">Sessions ce mois</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +12% depuis le mois dernier
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
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>12</h3>
                            <p class="text-muted mb-0">Nouveaux prestataires</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +3 depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Providers Table -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des prestataires</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                10 par page
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">10 par page</a></li>
                                <li><a class="dropdown-item" href="#">25 par page</a></li>
                                <li><a class="dropdown-item" href="#">50 par page</a></li>
                                <li><a class="dropdown-item" href="#">Tous</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="form-check-input">
                                        </th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Spécialité</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Sessions</th>
                                        <th scope="col">Note</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Sophie Martin</span>
                                            </div>
                                        </td>
                                        <td>Psychologue</td>
                                        <td>sophie.martin@example.com</td>
                                        <td>142</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.9</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Laurent Dubois</span>
                                            </div>
                                        </td>
                                        <td>Coach sportif</td>
                                        <td>laurent.dubois@example.com</td>
                                        <td>97</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.8</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Émilie Bernard</span>
                                            </div>
                                        </td>
                                        <td>Nutritionniste</td>
                                        <td>emilie.bernard@example.com</td>
                                        <td>84</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.8</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Thomas Leroy</span>
                                            </div>
                                        </td>
                                        <td>Conférencier</td>
                                        <td>thomas.leroy@example.com</td>
                                        <td>32</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.7</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Julie Moreau</span>
                                            </div>
                                        </td>
                                        <td>Prof de yoga</td>
                                        <td>julie.moreau@example.com</td>
                                        <td>68</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.7</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">En pause</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Philippe Durand</span>
                                            </div>
                                        </td>
                                        <td>Coach mental</td>
                                        <td>philippe.durand@example.com</td>
                                        <td>42</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.6</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        <td class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="user-avatar me-2 rounded-circle">
                                            <div>
                                                <span class="fw-medium">Marie Leclerc</span>
                                            </div>
                                        </td>
                                        <td>Art-thérapeute</td>
                                        <td>marie.leclerc@example.com</td>
                                        <td>24</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">4.5</span>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary">Inactif</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

                <!-- Charts and Statistics Row -->
                <div class="row mt-4">
                    <!-- Provider Categories Distribution -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par spécialité</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Provider Session Trends -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Sessions mensuelles</h5>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary active">6 mois</button>
                                    <button type="button" class="btn btn-outline-secondary">1 an</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="sessionsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Rated Providers and New Applications -->
                <div class="row mt-4">
                    <!-- Top Rated Providers -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Prestataires les mieux notés</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Sophie Martin</h6>
                                                <p class="mb-0 small text-muted">Psychologue</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">4.9</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">142 séances</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Laurent Dubois</h6>
                                                <p class="mb-0 small text-muted">Coach sportif</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">4.8</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">97 séances</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Émilie Bernard</h6>
                                                <p class="mb-0 small text-muted">Nutritionniste</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">4.8</span>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">84 séances</small>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-sm btn-primary">Voir le classement complet</a>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Applications -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Candidatures récentes</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Antoine Mercier</h6>
                                                <p class="mb-0 small text-muted">Coach en développement personnel</p>
                                                <small class="text-muted">Postulé le 02/03/2025</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Camille Fischer</h6>
                                                <p class="mb-0 small text-muted">Méditation pleine conscience</p>
                                                <small class="text-muted">Postulé le 01/03/2025</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/50/50" alt="Avatar" class="user-avatar me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Julien Petit</h6>
                                                <p class="mb-0 small text-muted">Ergonome</p>
                                                <small class="text-muted">Postulé le 28/02/2025</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-sm btn-primary">Voir toutes les candidatures</a>
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
                            <h6>Nouveau prestataire</h6>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Ajouter</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-calendar-plus fa-2x text-success"></i>
                            </div>
                            <h6>Planifier une session</h6>
                            <a href="#" class="btn btn-sm btn-outline-success mt-2">Créer</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-file-invoice fa-2x text-warning"></i>
                            </div>
                            <h6>Générer un rapport</h6>
                            <a href="#" class="btn btn-sm btn-outline-warning mt-2">Générer</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-envelope fa-2x text-info"></i>
                            </div>
                            <h6>Message groupé</h6>
                            <a href="#" class="btn btn-sm btn-outline-info mt-2">Envoyer</a>
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
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Santé mentale', 'Sport & Bien-être', 'Nutrition', 'Conférenciers', 'Coach professionnel', 'Autres'],
                datasets: [{
                    data: [32, 28, 15, 12, 8, 5],
                    backgroundColor: [
                        '#3a86ff',
                        '#8338ec',
                        '#ff006e',
                        '#fb5607',
                        '#ffbe0b',
                        '#38b000'
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

        // Sessions Chart
        const sessionsCtx = document.getElementById('sessionsChart').getContext('2d');
        const sessionsChart = new Chart(sessionsCtx, {
            type: 'line',
            data: {
                labels: ['Oct', 'Nov', 'Déc', 'Jan', 'Fév', 'Mar'],
                datasets: [{
                        label: 'Sessions 2025',
                        data: [320, 345, 380, 402, 425, 435],
                        borderColor: '#3a86ff',
                        backgroundColor: 'rgba(58, 134, 255, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Sessions 2024',
                        data: [280, 310, 330, 345, 365, 380],
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
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
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
