<?php
$title = "Gestion des sociétés";
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
                    <h1 class="h2">Gestion des sociétés</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Exporter</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Imprimer</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSocietyModal">
                            <i class="fas fa-plus"></i> Nouvelle société
                        </button>
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>184</h3>
                            <p class="text-muted mb-0">Total sociétés</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +5% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h3>142</h3>
                            <p class="text-muted mb-0">Contrats actifs</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +3% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <h3>28</h3>
                            <p class="text-muted mb-0">En attente</p>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-arrow-up"></i> +12% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3>325</h3>
                            <p class="text-muted mb-0">Utilisateurs</p>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-arrow-up"></i> +7% depuis le mois dernier
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Tools -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Rechercher une société...">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                    <select class="form-select form-select-sm w-auto">
                                        <option>Tous les statuts</option>
                                        <option>Actif</option>
                                        <option>En attente</option>
                                        <option>Inactif</option>
                                    </select>
                                    <select class="form-select form-select-sm w-auto">
                                        <option>Tous les types</option>
                                        <option>PME</option>
                                        <option>Grande entreprise</option>
                                        <option>Start-up</option>
                                    </select>
                                    <select class="form-select form-select-sm w-auto">
                                        <option>Tous les secteurs</option>
                                        <option>Technologie</option>
                                        <option>Finance</option>
                                        <option>Santé</option>
                                        <option>Education</option>
                                        <option>Retail</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Society Table -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Liste des sociétés</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"></i> Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Envoyer un email groupé</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Exporter en Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>Exporter en PDF</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash-alt me-2"></i>Supprimer sélectionnés</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>Société</th>
                                        <th>Contact</th>
                                        <th>Adresse</th>
                                        <th>Contrat</th>
                                        <th>Utilisateurs</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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
                                                <div class="company-icon bg-primary bg-opacity-10 text-primary me-3">TI</div>
                                                <div>
                                                    <h6 class="mb-0">TechInnov</h6>
                                                    <small class="text-muted">Tech</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>Sophie Martin</div>
                                            <small class="text-muted">s.martin@techinnov.fr</small>
                                        </td>
                                        <td>Paris, France</td>
                                        <td>Premium</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">24</span>
                                                <div class="progress flex-grow-1" style="height: 5px">
                                                    <div class="progress-bar bg-success" style="width: 80%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
                                                <div class="company-icon bg-success bg-opacity-10 text-success me-3">ES</div>
                                                <div>
                                                    <h6 class="mb-0">EcoSolutions</h6>
                                                    <small class="text-muted">Environment</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>Marc Dupont</div>
                                            <small class="text-muted">m.dupont@ecosolutions.com</small>
                                        </td>
                                        <td>Lyon, France</td>
                                        <td>Basic</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">18</span>
                                                <div class="progress flex-grow-1" style="height: 5px">
                                                    <div class="progress-bar bg-success" style="width: 60%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
                                                <div class="company-icon bg-warning bg-opacity-10 text-warning me-3">DW</div>
                                                <div>
                                                    <h6 class="mb-0">DigitalWave</h6>
                                                    <small class="text-muted">Digital Marketing</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>Julie Leroy</div>
                                            <small class="text-muted">j.leroy@digitalwave.fr</small>
                                        </td>
                                        <td>Nantes, France</td>
                                        <td>Basic</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">12</span>
                                                <div class="progress flex-grow-1" style="height: 5px">
                                                    <div class="progress-bar bg-warning" style="width: 40%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">En attente</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
                                                <div class="company-icon bg-info bg-opacity-10 text-info me-3">GL</div>
                                                <div>
                                                    <h6 class="mb-0">GreenLife</h6>
                                                    <small class="text-muted">Wellbeing</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>Pierre Moreau</div>
                                            <small class="text-muted">p.moreau@greenlife.fr</small>
                                        </td>
                                        <td>Bordeaux, France</td>
                                        <td>Starter</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">8</span>
                                                <div class="progress flex-grow-1" style="height: 5px">
                                                    <div class="progress-bar bg-info" style="width: 25%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
                                                <div class="company-icon bg-danger bg-opacity-10 text-danger me-3">SR</div>
                                                <div>
                                                    <h6 class="mb-0">SmartRetail</h6>
                                                    <small class="text-muted">Retail</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>Emma Bernard</div>
                                            <small class="text-muted">e.bernard@smartretail.com</small>
                                        </td>
                                        <td>Lille, France</td>
                                        <td>Premium</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">32</span>
                                                <div class="progress flex-grow-1" style="height: 5px">
                                                    <div class="progress-bar bg-success" style="width: 90%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">Actif</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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

                <!-- Charts Row -->
                <div class="row">
                    <!-- Society by Type -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Répartition par type</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Cette année
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Cette année</a></li>
                                        <li><a class="dropdown-item" href="#">Année précédente</a></li>
                                        <li><a class="dropdown-item" href="#">Toutes les années</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="societyTypeChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Society by Sector -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Répartition par secteur</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                        Cette année
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="dropdown-item" href="#">Cette année</a></li>
                                        <li><a class="dropdown-item" href="#">Année précédente</a></li>
                                        <li><a class="dropdown-item" href="#">Toutes les années</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="societySectorChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Activités récentes</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted">Aujourd'hui, 10:45</small>
                                        </div>
                                        <p class="mb-1">Nouveau contrat signé avec <strong>TechInnov</strong></p>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted">Aujourd'hui, 09:30</small>
                                        </div>
                                        <p class="mb-1">Mise à jour des informations de <strong>EcoSolutions</strong></p>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted">Hier, 16:20</small>
                                        </div>
                                        <p class="mb-1">Nouveau contact ajouté pour <strong>DigitalWave</strong></p>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted">Hier, 14:15</small>
                                        </div>
                                        <p class="mb-1">Contrat renouvelé avec <strong>SmartRetail</strong></p>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted">28/02/2025, 11:05</small>
                                        </div>
                                        <p class="mb-1">Nouvelle société créée : <strong>FitHealth</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-sm btn-primary">Voir toutes les activités</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Contrats par région</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Paris & Île-de-France</span>
                                        <span>45%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Auvergne-Rhône-Alpes</span>
                                        <span>18%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>PACA</span>
                                        <span>12%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Nouvelle-Aquitaine</span>
                                        <span>10%</< /span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Autres régions</span>
                                        <span>15%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
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

    <!-- Modal pour ajouter une société -->
    <div class="modal fade" id="addSocietyModal" tabindex="-1" aria-labelledby="addSocietyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSocietyModalLabel">Ajouter une nouvelle société</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="societyName" class="form-label">Nom de la société *</label>
                            <input type="text" class="form-control" id="societyName" required>
                        </div>
                        <div class="mb-3">
                            <label for="societySector" class="form-label">Secteur d'activité</label>
                            <select class="form-select" id="societySector">
                                <option selected>Choisir un secteur</option>
                                <option>Technologie</option>
                                <option>Finance</option>
                                <option>Santé</option>
                                <option>Education</option>
                                <option>Retail</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societySize" class="form-label">Taille de l'entreprise</label>
                                    <select class="form-select" id="societySize">
                                        <option selected>Choisir une taille</option>
                                        <option>1-10 employés</option>
                                        <option>11-50 employés</option>
                                        <option>51-200 employés</option>
                                        <option>201-500 employés</option>
                                        <option>501+ employés</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societyType" class="form-label">Type d'entreprise</label>
                                    <select class="form-select" id="societyType">
                                        <option selected>Choisir un type</option>
                                        <option>PME</option>
                                        <option>Grande entreprise</option>
                                        <option>Start-up</option>
                                        <option>Association</option>
                                        <option>Organisme public</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="societyAddress" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="societyAddress">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societyCity" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="societyCity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societyCountry" class="form-label">Pays</label>
                                    <input type="text" class="form-control" id="societyCountry" value="France">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="societyContactName" class="form-label">Nom du contact principal</label>
                            <input type="text" class="form-control" id="societyContactName">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societyContactEmail" class="form-label">Email du contact</label>
                                    <input type="email" class="form-control" id="societyContactEmail">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="societyContactPhone" class="form-label">Téléphone du contact</label>
                                    <input type="tel" class="form-control" id="societyContactPhone">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="societyContract" class="form-label">Type de contrat</label>
                            <select class="form-select" id="societyContract">
                                <option selected>Choisir un contrat</option>
                                <option>Starter</option>
                                <option>Basic</option>
                                <option>Premium</option>
                                <option>Enterprise</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="societyNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="societyNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Society Type Chart
        const typeCtx = document.getElementById('societyTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['PME', 'Grande entreprise', 'Start-up', 'Association', 'Organisme public'],
                datasets: [{
                    data: [45, 20, 25, 7, 3],
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#0dcaf0',
                        '#dc3545'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Society Sector Chart
        const sectorCtx = document.getElementById('societySectorChart').getContext('2d');
        const sectorChart = new Chart(sectorCtx, {
            type: 'bar',
            data: {
                labels: ['Technologie', 'Finance', 'Santé', 'Education', 'Retail', 'Autre'],
                datasets: [{
                    label: 'Nombre de sociétés',
                    data: [52, 38, 24, 18, 32, 20],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(13, 202, 240, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(108, 117, 125, 0.7)'
                    ],
                    borderColor: [
                        'rgb(13, 110, 253)',
                        'rgb(25, 135, 84)',
                        'rgb(255, 193, 7)',
                        'rgb(13, 202, 240)',
                        'rgb(220, 53, 69)',
                        'rgb(108, 117, 125)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Check all checkbox functionality
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('tbody .form-check-input');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>

</html>
