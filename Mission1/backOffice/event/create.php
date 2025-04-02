<?php
$title = "Créer une société";
include_once "../includes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include_once "../includes/sidebar.php"; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Entête -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <a href="/backoffice/event/event.php" class="btn btn-link text-decoration-none me-2">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="h2">Créer un évènement</h1>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Informations de l'évènement</h5>
                    </div>
                    <div class="card-body">
                        <form id="createEventForm">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="lieu" class="form-label">Lieu</label>
                                <input type="text" class="form-control" id="lieu" name="lieu" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control" id="type" name="type" required>
                            </div>
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-control" id="statut" name="statut" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="a_venir">À venir</option>
                                    <option value="termine">Terminé</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="association" class="form-label">Association</label>
                                <select class="form-control" id="association" name="association" required></select>
                            </div>
                            <button type="submit" class="btn btn-primary">Créer l'évènement</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createEventForm');
            association();
            form.addEventListener('submit', function(e) {
                e.preventDefault();
            

                // Validate form data
                const formData = {
                    nom: document.getElementById('nom').value,
                    date: document.getElementById('date').value,
                    lieu: document.getElementById('lieu').value,
                    type: document.getElementById('type').value,
                    statut: document.getElementById('statut').value,
                    id_association: document.getElementById('association').value
                };

                // Validate required fields
                if (!formData.nom || !formData.date || !formData.lieu || !formData.type || !formData.statut) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return;
                }

                fetch('../../api/event/create.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getToken()
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.id) {
                            alert('Événement créé avec succès!');
                            window.location.href = 'event.php';
                        } else {
                            alert('Erreur: ' + (data.error || 'Impossible de créer l\'événement'));
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la création de l\'événement');
                    });
            });


            // Récupération des associations pour le select

            function association() {
                const associationSelect = document.getElementById('association');

                fetch('../../api/association/getAll.php', {
                        headers: {
                            'Authorization': 'Bearer ' + getToken()
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(association => {
                            const option = document.createElement('option');
                            option.value = association.id;
                            option.textContent = association.name; // Ajout du nom de l'association
                            associationSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des associations:', error);
                        alert('Erreur lors du chargement des associations');
                    });
            }
        });
    </script>
</body>

</html>