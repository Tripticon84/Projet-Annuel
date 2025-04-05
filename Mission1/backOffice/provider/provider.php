<?php
$title = "Gestion des Prestataires";
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
                    <h1 class="h2">Gestion des Prestataires</h1>
                </div>

                <!-- Candidates Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Candidatures en attente</h5>
                        <div class="d-flex">
                            <div class="dropdown me-2">
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#">Nom (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Nom (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#">Date (récent)</a></li>
                                    <li><a class="dropdown-item" href="#">Date (ancien)</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nom/Prénom</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Tarif</th>
                                        <th scope="col">Disponibilité</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="candidateList">
                                    <!-- Les candidats seront insérés ici par JavaScript -->
                                    <tr>
                                        <td colspan="7" class="text-center">Chargement des candidats...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Verified Providers Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Prestataires vérifiés</h5>
                        <div class="d-flex mt-2 mt-sm-0  align-items-center">
                            <div class="input-group me-2" style="max-width: 200px;">
                                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Rechercher un prestataire" aria-label="Search">
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <a type="button" class="btn btn-sm btn-primary me-2" href="create.php">
                                <i class="fas fa-plus"></i> Ajouter un prestataire
                            </a>
                            <div class="dropdown">
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdownVerified">
                                    <li><a class="dropdown-item" href="#">Nom (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Nom (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#">Type (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Tarif (croissant)</a></li>
                                    <li><a class="dropdown-item" href="#">Tarif (décroissant)</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nom/Prénom</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Tarif</th>
                                        <th scope="col">Disponibilité</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="providerList">
                                    <!-- Les prestataires seront insérés ici par JavaScript -->
                                    <tr>
                                        <td colspan="7" class="text-center">Chargement des prestataires...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small" id="paginationInfo">Chargement des données...</span>
                        </div>
                        <nav aria-label="Table navigation">
                            <ul class="pagination pagination-sm mb-0" id="paginationList"></ul>
                        </nav>
                    </div>
                </div>

                <!-- Quick Action Cards -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Actions rapides</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-user-check fa-2x text-success"></i>
                            </div>
                            <h6>Vérifier les candidats</h6>
                            <button class="btn btn-sm btn-outline-success mt-2" onclick="scrollToCandidates()">Voir les candidats</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-envelope fa-2x text-warning"></i>
                            </div>
                            <h6>Contacter tous les prestataires</h6>
                            <a href="#" class="btn btn-sm btn-outline-warning mt-2">Composer</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal pour afficher les activités du prestataire -->
    <div class="modal fade" id="activitiesModal" tabindex="-1" aria-labelledby="activitiesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activitiesModalLabel">Activités du prestataire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Lieu</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Devis</th>
                                </tr>
                            </thead>
                            <tbody id="activityList">
                                <!-- Les activités seront insérées ici par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchCandidates();
            fetchVerifiedProviders();

            // Ajout d'écouteurs d'événements pour la recherche
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    fetchVerifiedProviders(this.value);
                }
            });

            // Écouteur pour le bouton de recherche
            document.querySelector('.btn-outline-secondary[type="button"]').addEventListener('click', function() {
                fetchVerifiedProviders(document.getElementById('searchInput').value);
            });
        });

        function scrollToCandidates() {
            document.querySelector('.card:first-of-type').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function fetchCandidates() {
            const candidateList = document.getElementById('candidateList');
            candidateList.innerHTML = '<tr><td colspan="7" class="text-center">Chargement des candidats...</td></tr>';

            fetch('../../api/provider/getCandidates.php', {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la récupération des candidats');
                    }
                    return response.json();
                })
                .then(data => {
                    candidateList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(candidate => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${candidate.id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0">${candidate.name} ${candidate.surname}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>${candidate.email || '-'}</td>
                                <td>${candidate.type || '-'}</td>
                                <td>${candidate.price ? candidate.price + ' €' : '-'}</td>
                                <td>${formatDateRange(candidate.start_date, candidate.end_date)}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-success me-1" onclick="acceptCandidate(${candidate.id})">
                                        <i class="fas fa-check"></i> Accepter
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Refuser
                                    </button>
                                </td>
                            `;
                            candidateList.appendChild(row);
                        });
                    } else {
                        candidateList.innerHTML = '<tr><td colspan="7" class="text-center">Aucun candidat en attente</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    candidateList.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement des candidats</td></tr>';
                });
        }

        let currentPage = 1;

        function fetchVerifiedProviders(search = '', page = 1) {
            currentPage = page;
            const providerList = document.getElementById('providerList');
            providerList.innerHTML = '<tr><td colspan="7" class="text-center">Chargement des prestataires...</td></tr>';

            let limit = 5;
            let offset = (page - 1) * limit;
            let url = `../../api/provider/getVerifiedProviders.php?limit=${limit}&offset=${offset}`;

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            fetch(url, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la récupération des prestataires');
                    }
                    return response.json();
                })
                .then(data => {
                    providerList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(provider => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${provider.id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0">${provider.name} ${provider.surname}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>${provider.email || '-'}</td>
                                <td>${provider.type || '-'}</td>
                                <td>${provider.price ? provider.price + ' €' : '-'}</td>
                                <td>${formatDateRange(provider.start_date, provider.end_date)}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" onclick="viewActivities(${provider.id}); return false;"><i class="fas fa-calendar-alt me-2"></i>Voir activités</a></li>
                                            <li><a class="dropdown-item" href="modify.php?id=${provider.id}"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-user-slash me-2"></i>Désactiver</a></li>
                                        </ul>
                                    </div>
                                </td>
                            `;
                            providerList.appendChild(row);
                        });

                        document.getElementById('paginationInfo').textContent = `Affichage de 1-${data.length} prestataires`;
                        updatePagination(data.length === limit, search);
                    } else {
                        providerList.innerHTML = '<tr><td colspan="7" class="text-center">Aucun prestataire vérifié trouvé</td></tr>';
                        document.getElementById('paginationInfo').textContent = 'Aucun prestataire vérifié trouvé';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    providerList.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement des prestataires</td></tr>';
                    document.getElementById('paginationInfo').textContent = 'Erreur lors du chargement des données';
                });
        }

        function updatePagination(hasMore, search = '') {
            const paginationList = document.getElementById('paginationList');
            paginationList.innerHTML = '';
            // Bouton précédent
            let prevItem = document.createElement('li');
            prevItem.className = 'page-item ' + (currentPage === 1 ? 'disabled' : '');
            prevItem.innerHTML = `<a class="page-link" href="#" onclick="fetchVerifiedProviders('${search}', ${currentPage - 1}); return false;">Précédent</a>`;
            paginationList.appendChild(prevItem);
            // Bouton suivant
            let nextItem = document.createElement('li');
            nextItem.className = 'page-item ' + (!hasMore ? 'disabled' : '');
            nextItem.innerHTML = `<a class="page-link" href="#" onclick="fetchVerifiedProviders('${search}', ${currentPage + 1}); return false;">Suivant</a>`;
            paginationList.appendChild(nextItem);
        }

        function acceptCandidate(id) {
            if (confirm('Êtes-vous sûr de vouloir accepter ce candidat ?')) {
                fetch('../../api/provider/acceptCandidate.php', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify({
                            prestataire_id: id,
                            est_candidat: true
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.prestataire || (data.message && data.message.includes('accepté'))) {
                            alert('Candidat accepté avec succès.');
                            fetchCandidates();
                            fetchVerifiedProviders();
                        } else {
                            alert('Erreur lors de l\'acceptation du candidat. Veuillez réessayer.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de l\'acceptation du candidat.');
                    });
            }
        }

        function viewActivities(providerId) {
            const activityList = document.getElementById('activityList');
            activityList.innerHTML = '<tr><td colspan="6" class="text-center">Chargement des activités...</td></tr>';

            const modal = new bootstrap.Modal(document.getElementById('activitiesModal'));
            modal.show();

            fetch(`/api/provider/getActivite.php?id=${providerId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    activityList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(activity => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                    <td>${activity.activite_id}</td>
                    <td>${activity.name}</td>
                    <td>${formatDate(activity.date)}</td>
                    <td>${activity.place || '-'}</td>
                    <td>${activity.type || '-'}</td>
                    <td>${activity.id_estimate ? `<a href="#" class="btn btn-sm btn-outline-primary">Voir devis #${activity.id_estimate}</a>` : '-'}</td>
                `;
                            activityList.appendChild(row);
                        });
                    } else {
                        activityList.innerHTML = '<tr><td colspan="6" class="text-center">Aucune activité trouvée pour ce prestataire</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    activityList.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des activités</td></tr>';
                });
        }

        function formatDateRange(startDate, endDate) {
            if (!startDate && !endDate) return '-';

            const formatDate = (dateStr) => {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleDateString('fr-FR');
            };

            return `${formatDate(startDate)} - ${formatDate(endDate)}`;
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR');
        }
    </script>

    <style>
        .provider-card {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }

        .provider-avatar {
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

        .provider-info {
            display: flex;
            flex-direction: column;
        }

        .provider-name {
            font-weight: 600;
            color: #333;
        }

        .provider-id {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .stat-card {
            position: relative;
            padding: 1rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            margin-top: 0.5rem;
        }
    </style>
</body>

</html>
