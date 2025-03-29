<?php
$title = "Gestion des Signalements";
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
                    <h1 class="h2">Gestion des Signalements</h1>
                </div>

                <!-- Statistiques rapides -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-danger-subtle text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <p class="text-muted mb-0">Signalements en attente</p>
                            <h3 id="pendingCount">-</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-success-subtle text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="text-muted mb-0">Signalements traités</p>
                            <h3 id="resolvedCount">-</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-info-subtle text-info">
                                <i class="fas fa-building"></i>
                            </div>
                            <p class="text-muted mb-0">Entreprises concernées</p>
                            <h3 id="companiesCount">-</h3>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Signalements en attente</h5>
                        <div class="d-flex">
                            <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort"></i> Trier
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#" onclick="sortReports('date', 'desc'); return false;">Date (récent)</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="sortReports('date', 'asc'); return false;">Date (ancien)</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="sortReports('company', 'asc'); return false;">Entreprise (A-Z)</a></li>
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
                                        <th scope="col">Date</th>
                                        <th scope="col">Entreprise</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingReportsList">
                                    <tr>
                                        <td colspan="6" class="text-center">Chargement des signalements...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Processed Reports Table -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Signalements traités</h5>
                        <div class="d-flex mt-2 mt-sm-0 align-items-center">
                            <div class="input-group me-2" style="max-width: 200px;">
                                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Rechercher..." aria-label="Search">
                                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="searchReports()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="#" onclick="filterReports('all'); return false;">Tous</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterReports('month'); return false;">Ce mois</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterReports('quarter'); return false;">Ce trimestre</a></li>
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
                                        <th scope="col">Date signalement</th>
                                        <th scope="col">Date traitement</th>
                                        <th scope="col">Entreprise</th>
                                        <th scope="col">Description</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="processedReportsList">
                                    <tr>
                                        <td colspan="6" class="text-center">Chargement des signalements...</td>
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
                                <i class="fas fa-chart-pie fa-2x text-primary"></i>
                            </div>
                            <h6>Générer un rapport</h6>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="generateReport()">Créer un rapport</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-file-export fa-2x text-success"></i>
                            </div>
                            <h6>Exporter les données</h6>
                            <a href="#" class="btn btn-sm btn-outline-success mt-2" onclick="exportData(); return false;">Exporter</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <div class="mb-3">
                                <i class="fas fa-bell fa-2x text-warning"></i>
                            </div>
                            <h6>Configurer les alertes</h6>
                            <a href="#" class="btn btn-sm btn-outline-warning mt-2">Configurer</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal pour afficher les détails d'un signalement -->
    <div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDetailModalLabel">Détails du signalement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> <span id="modal-report-id"></span></p>
                            <p><strong>Date de signalement:</strong> <span id="modal-report-date"></span></p>
                            <p><strong>Entreprise:</strong> <span id="modal-report-company"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Statut:</strong> <span id="modal-report-status"></span></p>
                            <p><strong>Date de traitement:</strong> <span id="modal-report-processed-date">-</span></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6>Description du problème:</h6>
                        <div class="p-3 bg-light rounded" id="modal-report-problem"></div>
                    </div>
                    <div id="actionSection">
                        <h6>Actions:</h6>
                        <div class="mb-3">
                            <label for="treatmentNotes" class="form-label">Notes de traitement:</label>
                            <textarea class="form-control" id="treatmentNotes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reportStatus" class="form-label">Changer le statut:</label>
                            <select class="form-select" id="reportStatus">
                                <option value="pending">En attente</option>
                                <option value="processing">En cours de traitement</option>
                                <option value="resolved">Résolu</option>
                                <option value="archived">Archivé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Enregistrer les modifications</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchPendingReports();
            fetchProcessedReports();
            fetchStatistics();

            // Écouteur pour le bouton de recherche
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchReports();
                }
            });
        });

        function fetchStatistics() {
            // Cette fonction récupérerait des statistiques sur les signalements
            fetch('../../api/report/getStatistics.php', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('pendingCount').textContent = data.pending || '0';
                    document.getElementById('resolvedCount').textContent = data.resolved || '0';
                    document.getElementById('companiesCount').textContent = data.companies || '0';
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des statistiques:', error);
                });
        }

        let currentPage = 1;

        function fetchPendingReports() {
            const reportsList = document.getElementById('pendingReportsList');
            reportsList.innerHTML = '<tr><td colspan="6" class="text-center">Chargement des signalements...</td></tr>';

            fetch('../../api/report/getPendingReports.php', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    reportsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(report => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td>${report.signalement_id}</td>
                            <td>${formatDate(report.date_signalement)}</td>
                            <td>${report.nom_societe || 'Non spécifié'}</td>
                            <td>${truncateText(report.probleme, 50)}</td>
                            <td><span class="badge bg-warning">En attente</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-primary me-1" onclick="viewReportDetails(${report.signalement_id})">
                                    <i class="fas fa-eye"></i> Détails
                                </button>
                                <button class="btn btn-sm btn-success" onclick="markAsProcessed(${report.signalement_id})">
                                    <i class="fas fa-check"></i> Traiter
                                </button>
                            </td>
                        `;
                            reportsList.appendChild(row);
                        });
                    } else {
                        reportsList.innerHTML = '<tr><td colspan="6" class="text-center">Aucun signalement en attente</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    reportsList.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des signalements</td></tr>';
                });
        }

        function fetchProcessedReports(search = '', page = 1, filter = 'all') {
            currentPage = page;
            const reportsList = document.getElementById('processedReportsList');
            reportsList.innerHTML = '<tr><td colspan="6" class="text-center">Chargement des signalements...</td></tr>';

            let limit = 5;
            let offset = (page - 1) * limit;
            let url = `../../api/report/getProcessedReports.php?limit=${limit}&offset=${offset}`;

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            if (filter && filter !== 'all') {
                url += '&filter=' + encodeURIComponent(filter);
            }

            fetch(url, {
                    headers: {
                        'Authorization': 'Bearer ' + getToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    reportsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(report => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td>${report.signalement_id}</td>
                            <td>${formatDate(report.date_signalement)}</td>
                            <td>${report.date_traitement ? formatDate(report.date_traitement) : '-'}</td>
                            <td>${report.nom_societe || 'Non spécifié'}</td>
                            <td>${truncateText(report.probleme, 50)}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="viewReportDetails(${report.signalement_id}); return false;"><i class="fas fa-eye me-2"></i>Voir détails</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF(${report.signalement_id}); return false;"><i class="fas fa-file-pdf me-2"></i>Générer PDF</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="archiveReport(${report.signalement_id}); return false;"><i class="fas fa-archive me-2"></i>Archiver</a></li>
                                    </ul>
                                </div>
                            </td>
                        `;
                            reportsList.appendChild(row);
                        });

                        document.getElementById('paginationInfo').textContent = `Affichage de ${offset+1}-${offset+data.length} signalements`;
                        updatePagination(data.length === limit, search, filter);
                    } else {
                        reportsList.innerHTML = '<tr><td colspan="6" class="text-center">Aucun signalement traité trouvé</td></tr>';
                        document.getElementById('paginationInfo').textContent = 'Aucun signalement traité trouvé';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    reportsList.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur lors du chargement des signalements</td></tr>';
                    document.getElementById('paginationInfo').textContent = 'Erreur lors du chargement des données';
                });
        }

        function viewReportDetails(reportId) {
            fetch(`../../api/report/getReportDetails.php?id=${reportId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + getToken()
                    }
                })
                .then(response => response.json())
                .then(report => {
                    document.getElementById('modal-report-id').textContent = report.signalement_id;
                    document.getElementById('modal-report-date').textContent = formatDate(report.date_signalement);
                    document.getElementById('modal-report-company').textContent = report.nom_societe || 'Non spécifié';
                    document.getElementById('modal-report-problem').textContent = report.probleme;

                    const status = report.status || 'pending';
                    let statusText = 'En attente';
                    let statusClass = 'text-warning';

                    if (status === 'processing') {
                        statusText = 'En cours de traitement';
                        statusClass = 'text-info';
                    } else if (status === 'resolved') {
                        statusText = 'Résolu';
                        statusClass = 'text-success';
                    } else if (status === 'archived') {
                        statusText = 'Archivé';
                        statusClass = 'text-secondary';
                    }

                    document.getElementById('modal-report-status').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
                    document.getElementById('modal-report-processed-date').textContent = report.date_traitement ? formatDate(report.date_traitement) : '-';

                    // Préparer le formulaire
                    document.getElementById('reportStatus').value = status;
                    document.getElementById('treatmentNotes').value = report.notes || '';

                    // Configurer le bouton de sauvegarde
                    document.getElementById('saveChangesBtn').onclick = function() {
                        updateReportStatus(reportId);
                    };

                    const modal = new bootstrap.Modal(document.getElementById('reportDetailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des détails du signalement');
                });
        }

        function updateReportStatus(reportId) {
            const status = document.getElementById('reportStatus').value;
            const notes = document.getElementById('treatmentNotes').value;

            fetch('../../api/report/updateReportStatus.php', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + getToken()
                    },
                    body: JSON.stringify({
                        signalement_id: reportId,
                        status: status,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Statut du signalement mis à jour avec succès');
                        bootstrap.Modal.getInstance(document.getElementById('reportDetailModal')).hide();
                        fetchPendingReports();
                        fetchProcessedReports();
                        fetchStatistics();
                    } else {
                        alert('Erreur lors de la mise à jour du statut du signalement');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la mise à jour du statut');
                });
        }

        function markAsProcessed(reportId) {
            if (confirm('Êtes-vous sûr de vouloir marquer ce signalement comme traité?')) {
                fetch('../../api/report/updateReportStatus.php', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify({
                            signalement_id: reportId,
                            status: 'resolved'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Signalement marqué comme traité avec succès');
                            fetchPendingReports();
                            fetchProcessedReports();
                            fetchStatistics();
                        } else {
                            alert('Erreur lors du traitement du signalement');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors du traitement du signalement');
                    });
            }
        }

        function archiveReport(reportId) {
            if (confirm('Êtes-vous sûr de vouloir archiver ce signalement?')) {
                fetch('../../api/report/updateReportStatus.php', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify({
                            signalement_id: reportId,
                            status: 'archived'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Signalement archivé avec succès');
                            fetchProcessedReports();
                            fetchStatistics();
                        } else {
                            alert('Erreur lors de l\'archivage du signalement');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de l\'archivage du signalement');
                    });
            }
        }

        function searchReports() {
            const searchTerm = document.getElementById('searchInput').value;
            fetchProcessedReports(searchTerm, 1);
        }

        function sortReports(field, order) {
            // Cette fonction trierait les signalements selon le champ et l'ordre spécifiés
            console.log(`Tri par ${field} en ordre ${order}`);
            // Implémenter la logique de tri
        }

        function filterReports(period) {
            fetchProcessedReports('', 1, period);
        }

        function updatePagination(hasMore, search = '', filter = 'all') {
            const paginationList = document.getElementById('paginationList');
            paginationList.innerHTML = '';

            // Bouton précédent
            let prevItem = document.createElement('li');
            prevItem.className = 'page-item ' + (currentPage === 1 ? 'disabled' : '');
            prevItem.innerHTML = `<a class="page-link" href="#" onclick="fetchProcessedReports('${search}', ${currentPage - 1}, '${filter}'); return false;">Précédent</a>`;
            paginationList.appendChild(prevItem);

            // Bouton suivant
            let nextItem = document.createElement('li');
            nextItem.className = 'page-item ' + (!hasMore ? 'disabled' : '');
            nextItem.innerHTML = `<a class="page-link" href="#" onclick="fetchProcessedReports('${search}', ${currentPage + 1}, '${filter}'); return false;">Suivant</a>`;
            paginationList.appendChild(nextItem);
        }

        function generateReport() {
            alert('Fonctionnalité de génération de rapport en cours de développement');
        }

        function exportData() {
            alert('Fonctionnalité d\'exportation de données en cours de développement');
        }

        function generatePDF(reportId) {
            alert(`Génération de PDF pour le signalement #${reportId} en cours de développement`);
        }

        function truncateText(text, maxLength) {
            if (!text) return '';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    </script>

    <style>
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

        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
</body>

</html>
