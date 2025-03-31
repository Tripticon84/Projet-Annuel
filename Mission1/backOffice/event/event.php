<?php
$title = "Gestion des Événements";
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
                    <h1 class="h2">Gestion des Événements</h1>
                </div>

                <!-- Upcoming Events Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Événements à venir</h5>
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
                                        <th scope="col">Nom</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Lieu</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Association</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="upcomingEventsList">
                                    <!-- Les événements à venir seront insérés ici par JavaScript -->
                                    <tr>
                                        <td colspan="7" class="text-center">Chargement des événements...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- All Events Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tous les événements</h5>
                        <div class="d-flex mt-2 mt-sm-0 align-items-center">
                            <div class="input-group me-2" style="max-width: 200px;">
                                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Rechercher un événement" aria-label="Search">
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="dropdown">
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdownAll">
                                    <li><a class="dropdown-item" href="#">Nom (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#">Nom (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#">Date (récent)</a></li>
                                    <li><a class="dropdown-item" href="#">Date (ancien)</a></li>
                                    <li><a class="dropdown-item" href="#">Type (A-Z)</a></li>
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
                                        <th scope="col">Nom</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Lieu</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Association</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="allEventsList">
                                    <!-- Tous les événements seront insérés ici par JavaScript -->
                                    <tr>
                                        <td colspan="7" class="text-center">Chargement des événements...</td>
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
                                <i class="fas fa-calendar-plus fa-2x text-success"></i>
                            </div>
                            <h6>Planifier un événement</h6>
                            <a href="create.php" class="btn btn-sm btn-outline-success mt-2">Créer</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-file-export fa-2x text-primary"></i>
                            </div>
                            <h6>Exporter données</h6>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Exporter</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-users fa-2x text-warning"></i>
                            </div>
                            <h6>Gérer les participations</h6>
                            <a href="#" class="btn btn-sm btn-outline-warning mt-2">Voir</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal pour afficher les collaborateurs participant à l'événement -->
    <div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="participantsModalLabel">Participants à l'événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="participantsList">
                                <!-- Les participants seront insérés ici par JavaScript -->
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
            fetchUpcomingEvents();
            fetchAllEvents();

            // Ajout d'écouteurs d'événements pour la recherche
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    fetchAllEvents(this.value);
                }
            });

            // Écouteur pour le bouton de recherche
            document.querySelector('.btn-outline-secondary[type="button"]').addEventListener('click', function() {
                fetchAllEvents(document.getElementById('searchInput').value);
            });
        });

        function fetchUpcomingEvents() {
            const upcomingEventsList = document.getEventById('upcomingEventsList');
            upcomingEventsList.innerHTML = '<tr><td colspan="7" class="text-center">Chargement des événements...</td></tr>';

            fetch('../../api/event/upcomingEvents.php', {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la récupération des événements à venir');
                    }
                    return response.json();
                })
                .then(data => {
                    upcomingEventsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(event => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${event.evenement_id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0">${event.nom}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>${formatDate(event.date)}</td>
                                <td>${event.lieu || '-'}</td>
                                <td>${event.type || '-'}</td>
                                <td>${event.association_name || '-'}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="viewParticipants(${event.evenement_id})">
                                        <i class="fas fa-users"></i> Participants
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                </td>
                            `;
                            upcomingEventsList.appendChild(row);
                        });
                    } else {
                        upcomingEventsList.innerHTML = '<tr><td colspan="7" class="text-center">Aucun événement à venir</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    upcomingEventsList.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement des événements</td></tr>';
                });
        }

        let currentPage = 1;

        function fetchAllEvents(search = '', page = 1) {
            currentPage = page;
            const allEventsList = document.getElementById('allEventsList');
            allEventsList.innerHTML = '<tr><td colspan="7" class="text-center">Chargement des événements...</td></tr>';

            let limit = 5;
            let offset = (page - 1) * limit;
            let url = `../../api/event/getAll.php?limit=${limit}&offset=${offset}`;

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
                        throw new Error('Erreur lors de la récupération des événements');
                    }
                    return response.json();
                })
                .then(data => {
                    allEventsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(event => {
                            const row = document.createEvent('tr');
                            row.innerHTML = `
                                <td>${event.evenement_id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0">${event.nom}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>${formatDate(event.date)}</td>
                                <td>${event.lieu || '-'}</td>
                                <td>${event.type || '-'}</td>
                                <td>${event.association_name || '-'}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" onclick="viewParticipants(${event.evenement_id}); return false;"><i class="fas fa-users me-2"></i>Voir participants</a></li>
                                            <li><a class="dropdown-item" href="modify.php?id=${event.evenement_id}"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href=""><i class="fas fa-trash-alt me-2"></i>Supprimer</a></li>
                                        </ul>
                                    </div>
                                </td>
                            `;
                            allEventsList.appendChild(row);
                        });

                        document.getEventById('paginationInfo').textContent = `Affichage de 1-${data.length} événements`;
                        updatePagination(data.length === limit, search);
                    } else {
                        allEventsList.innerHTML = '<tr><td colspan="7" class="text-center">Aucun événement trouvé</td></tr>';
                        document.getEventById('paginationInfo').textContent = 'Aucun événement trouvé';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    allEventsList.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement des événements</td></tr>';
                    document.getEventById('paginationInfo').textContent = 'Erreur lors du chargement des données';
                });
        }

        function updatePagination(hasMore, search = '') {
            const paginationList = document.getEventById('paginationList');
            paginationList.innerHTML = '';
            // Bouton précédent
            let prevItem = document.createEvent('li');
            prevItem.className = 'page-item ' + (currentPage === 1 ? 'disabled' : '');
            prevItem.innerHTML = `<a class="page-link" href="#" onclick="fetchAllEvents('${search}', ${currentPage - 1}); return false;">Précédent</a>`;
            paginationList.appendChild(prevItem);
            // Bouton suivant
            let nextItem = document.createEvent('li');
            nextItem.className = 'page-item ' + (!hasMore ? 'disabled' : '');
            nextItem.innerHTML = `<a class="page-link" href="#" onclick="fetchAllEvents('${search}', ${currentPage + 1}); return false;">Suivant</a>`;
            paginationList.appendChild(nextItem);
        }

        function viewParticipants(eventId) {
            const participantsList = document.getElementById('participantsList');
            participantsList.innerHTML = '<tr><td colspan="6" class="text-center">Chargement des participants...</td></tr>';

            const modal = new bootstrap.Modal(document.getElementById('participantsModal'));
            modal.show();

            fetch(`../../api/event/getParticipants.php?id=${eventId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    participantsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(participant => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${participant.id_collaborateur}</td>
                                <td>${participant.nom || '-'}</td>
                                <td>${participant.prenom || '-'}</td>
                                <td>${participant.email || '-'}</td>
                                <td>${participant.telephone || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeParticipant(${eventId}, ${participant.id_collaborateur})">
                                        <i class="fas fa-user-minus"></i> Retirer
                                    </button>
                                </td>
                            `;
                            participantsList.appendChild(row);
                        });
                    } else {
                        participantsList.innerHTML = '<tr><td colspan="6" class="text-center">Aucun participant trouvé pour cet événement</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    participantsList.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des participants</td></tr>';
                });
        }

        function removeParticipant(eventId, collaboratorId) {
            if (confirm('Êtes-vous sûr de vouloir retirer ce participant de l\'événement ?')) {
                fetch('../../api/event/removeParticipant.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + getToken()
                    },
                    body: JSON.stringify({
                        id_evenement: eventId,
                        id_collaborateur: collaboratorId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Participant retiré avec succès.');
                        viewParticipants(eventId); // Rafraîchir la liste des participants
                    } else {
                        alert('Erreur lors du retrait du participant. Veuillez réessayer.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors du retrait du participant.');
                });
            }
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR');
        }
    </script>

    <style>
        .event-card {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }

        .event-icon {
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

        .event-info {
            display: flex;
            flex-direction: column;
        }

        .event-name {
            font-weight: 600;
            color: #333;
        }

        .event-id {
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
