<?php
$title = "Gestion des Administrateurs";
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
                                        <th scope="col" width="40">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th scope="col">Administrateur</th>
                                        <th scope="col" class="text-end" width="80">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="adminList">
                                    <!-- Les administrateurs seront insérés ici par JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script>
        // Fetch Admin List
        fetch('../../api/admin/getAll.php')
            .then(response => response.json())
            .then(data => {
                const adminList = document.getElementById('adminList');
                data.forEach(admin => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="admin-card">
                                <div class="admin-avatar">${admin.username.charAt(0).toUpperCase()}</div>
                                <div class="admin-info">
                                    <div class="admin-username">${admin.username}</div>
                                    <div class="admin-id">ID-${admin.id}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteAdmin(${admin.id}); return false;"><i class="fas fa-trash me-2"></i>Supprimer</a></li>
                                </ul>
                            </div>
                        </td>
                    `;
                    adminList.appendChild(row);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des administrateurs:', error));

        // Function to delete admin with POST request
        function deleteAdmin(adminId) {
            if(confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')) {
                fetch('../../api/admin/delete.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: adminId })
                })
                .then(response => response.json())
                .then(data => {
                    // Vérifie si la réponse contient un indicateur de succès ou un message contenant "deleted"
                    if(data.success || (data.message && (data.message.includes('deleted') || data.message.includes('supprimé')))) {
                        alert('Administrateur supprimé avec succès.');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression. Veuillez réessayer.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la suppression.');
                });
            }
        }
    </script>
    <style>
        .admin-card {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        .admin-info {
            display: flex;
            flex-direction: column;
        }
        .admin-username {
            font-weight: 600;
            color: #333;
        }
        .admin-id {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
</body>
</html>
