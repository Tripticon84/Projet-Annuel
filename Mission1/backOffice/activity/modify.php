<?php
$title = "Modifier une activité";
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
                    <h1 class="h2">Modifier une activité</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="activity.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div id="loadingIndicator" class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des données de l'activité...</p>
                </div>

                <!-- Form -->
                <div id="activityForm" class="card" style="display: none;">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Informations de l'activité</h5>
                    </div>
                    <div class="card-body">
                        <form id="modifyActivityForm">
                            <input type="hidden" id="activite_id" name="activite_id"></input>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom de l'activité <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Type d'activité</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="">Sélectionnez un type</option>
                                        <option value="Atelier">Atelier</option>
                                        <option value="Sport">Sport</option>
                                        <option value="Culture">Culture</option>
                                        <option value="Conférence">Conférence</option>
                                        <option value="Divertissement">Divertissement</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="lieu" class="form-label">Lieu</label>
                                    <input type="text" class="form-control" id="lieu" name="lieu">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nb_max_participants" class="form-label">Nombre maximum de participants</label>
                                    <input type="number" class="form-control" id="nb_max_participants" name="nb_max_participants" min="1">
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" id="deleteButton" class="btn btn-danger me-auto">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="notFoundMessage" class="alert alert-danger my-4" style="display: none;">
                    <h4 class="alert-heading">Activité non trouvée</h4>
                    <p>L'activité demandée n'existe pas ou a été supprimée.</p>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <a href="activity.php" class="btn btn-outline-danger">Retour à la liste</a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupération de l'ID de l'activité depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const activityId = urlParams.get('id');

            if (!activityId) {
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('notFoundMessage').style.display = 'block';
                return;
            }

            // Récupération des données de l'activité
            fetch(`../../api/activity/getOne.php?activite_id=${activityId}`, {
                headers: {
                    'Authorization': 'Bearer ' + getToken()
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Activité non trouvée');
                    }
                    return response.json();
                })
                .then(activity => {
                    if (!activity) {
                        throw new Error('Activité non trouvée');
                    }
                    console.log(activity);

                    // Remplir le formulaire avec les données de l'activité
                    document.getElementById('activite_id').value = activity.activite_id;
                    document.getElementById('nom').value = activity.nom || '';
                    document.getElementById('description').value = activity.description || '';
                    document.getElementById('lieu').value = activity.lieu || '';
                    document.getElementById('type').value = activity.type || '';
                    document.getElementById('nb_max_participants').value = activity.nb_max_participants || '';

                    if (activity.date) {
                        // Format date value (YYYY-MM-DD)
                        const date = new Date(activity.date);
                        document.getElementById('date').value = date.toISOString().split('T')[0];
                    }

                    // Afficher le formulaire
                    document.getElementById('loadingIndicator').style.display = 'none';
                    document.getElementById('activityForm').style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('loadingIndicator').style.display = 'none';
                    document.getElementById('notFoundMessage').style.display = 'block';
                });

            // Gestion de la soumission du formulaire
            const form = document.getElementById('modifyActivityForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const activityData = {};

                formData.forEach((value, key) => {
                    activityData[key] = value;
                });
                activityData.activite_id = activityId;

                console.log(activityData);
                fetch('../../api/activity/update.php', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify(activityData),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Activité modifiée avec succès!');
                            // Recharger les données pour afficher les modifications
                            window.location.reload();
                        } else {
                            alert('Erreur: ' + (data.error || 'Impossible de modifier l\'activité'));
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la modification de l\'activité.');
                    });
            });

            // Gestion du bouton de suppression
            document.getElementById('deleteButton').addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette activité? Cette action est irréversible.')) {
                    fetch('../../api/activity/delete.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify({ activite_id: activityId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Activité supprimée avec succès!');
                            window.location.href = 'activity.php';
                        } else {
                            alert('Erreur: ' + (data.error || 'Impossible de supprimer l\'activité'));
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la suppression de l\'activité.');
                    });
                }
            });
        });
    </script>
</body>

</html>
