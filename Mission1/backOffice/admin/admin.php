<?php
$title = "Gestion des Administrateurs";
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
                    <h1 class="h2">Gestion des Administrateurs</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="input-group me-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Rechercher un administrateur..." aria-label="Search">
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a type="button" class="btn btn-sm btn-primary" href="create.php">
                        <i class="fas fa-plus"></i> Nouvel Administrateur
                        </a>
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3>42</h3>
                            <p class="text-muted mb-0">Administrateurs système</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +3 depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <h3>38</h3>
                            <p class="text-muted mb-0">Actifs ce mois</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +2 depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>5</h3>
                            <p class="text-muted mb-0">Nouveaux ce mois</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +2 depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>96%</h3>
                            <p class="text-muted mb-0">Conformité sécurité</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +4% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Admin Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Liste des Administrateurs</h5>
                        <div class="d-flex">
                            <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filtre
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li>
                                        <h6 class="dropdown-header">Niveau d'accès</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Tous les niveaux</a></li>
                                    <li><a class="dropdown-item" href="#">Super Admin</a></li>
                                    <li><a class="dropdown-item" href="#">Admin Système</a></li>
                                    <li><a class="dropdown-item" href="#">Admin Base de données</a></li>
                                    <li><a class="dropdown-item" href="#">Admin Sécurité</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header">Statut</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Tous les statuts</a></li>
                                    <li><a class="dropdown-item" href="#">Actifs</a></li>
                                    <li><a class="dropdown-item" href="#">Inactifs</a></li>
                                    <li><a class="dropdown-item" href="#">En attente</a></li>
                                </ul>
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort"></i> Tri
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#">Nom (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Nom (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#">Date d'ajout (récent)</a></li>
                                    <li><a class="dropdown-item" href="#">Date d'ajout (ancien)</a></li>
                                    <li><a class="dropdown-item" href="#">Niveau d'accès (élevé-bas)</a></li>
                                    <li><a class="dropdown-item" href="#">Niveau d'accès (bas-élevé)</a></li>
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
                                        <th scope="col">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th scope="col">Administrateur</th>
                                        <th scope="col">Niveau d'accès</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Département</th>
                                        <th scope="col">Date d'ajout</th>
                                        <th scope="col">Dernière connexion</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Alexandre Dupont</h6>
                                                    <span class="text-muted small">ID-1001</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">Super Admin</span></td>
                                        <td>a.dupont@businesscare.fr</td>
                                        <td>Direction IT</td>
                                        <td>05/01/2024</td>
                                        <td>Aujourd'hui 09:45</td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Marie Laurent</h6>
                                                    <span class="text-muted small">ID-1002</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Admin Système</span></td>
                                        <td>m.laurent@businesscare.fr</td>
                                        <td>Infrastructure</td>
                                        <td>12/01/2024</td>
                                        <td>Aujourd'hui 08:30</td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Philippe Martin</h6>
                                                    <span class="text-muted small">ID-1003</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">Admin Base de données</span></td>
                                        <td>p.martin@businesscare.fr</td>
                                        <td>Données</td>
                                        <td>18/01/2024</td>
                                        <td>Hier 16:20</td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Claire Dubois</h6>
                                                    <span class="text-muted small">ID-1004</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning text-dark">Admin Sécurité</span></td>
                                        <td>c.dubois@businesscare.fr</td>
                                        <td>Sécurité</td>
                                        <td>25/01/2024</td>
                                        <td>Aujourd'hui 10:15</td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Thomas Bernard</h6>
                                                    <span class="text-muted small">ID-1005</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">Admin Système</span></td>
                                        <td>t.bernard@businesscare.fr</td>
                                        <td>Infrastructure</td>
                                        <td>02/02/2024</td>
                                        <td>Hier 14:50</td>
                                        <td><span class="badge bg-warning text-dark">En attente</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-success" href="#"><i class="fas fa-user-check me-2"></i>Activer</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-2">
                                                <div>
                                                    <h6 class="mb-0">Sophie Richard</h6>
                                                    <span class="text-muted small">ID-1006</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary">Admin Support</span></td>
                                        <td>s.richard@businesscare.fr</td>
                                        <td>Support</td>
                                        <td>10/02/2024</td>
                                        <td>Il y a 3 jours</td>
                                        <td><span class="badge bg-danger">Inactif</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i>Permissions</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-success" href="#"><i class="fas fa-user-check me-2"></i>Réactiver</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">Affichage de 1-6 sur 42 administrateurs</span>
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
                    <!-- Admin Distribution By Department -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Répartition par département</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="departmentChart" height="260"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Login Activity -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Activité de connexion</h5>
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
                    <!-- Admin Permissions -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Administrateurs avec le plus de permissions</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Alexandre Dupont</h6>
                                                <p class="mb-0 small text-muted">Super Admin | Direction IT</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-danger">42 permissions</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Claire Dubois</h6>
                                                <p class="mb-0 small text-muted">Admin Sécurité | Sécurité</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning text-dark">36 permissions</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Marie Laurent</h6>
                                                <p class="mb-0 small text-muted">Admin Système | Infrastructure</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary">31 permissions</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Philippe Martin</h6>
                                                <p class="mb-0 small text-muted">Admin Base de données | Données</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-info">28 permissions</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="/api/placeholder/40/40" alt="Avatar" class="rounded-circle me-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Thomas Bernard</h6>
                                                <p class="mb-0 small text-muted">Admin Système | Infrastructure</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary">24 permissions</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-sm btn-primary">Gérer les permissions</a>
                            </div>
                        </div>
                    </div>
