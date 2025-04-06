<?php

// Inclure l'en-tête
require_once 'includes/head.php';
require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h1 class="card-title">Catalogue de services</h1>
                    <p class="card-text">Découvrez les prestations disponibles et réservez directement en ligne.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filtres de gauche -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <h6>Catégories</h6>
                    <div class="list-group">
                        <a href="catalogue.php" class="list-group-item list-group-item-action">
                            Toutes les catégories
                        </a>
                    </div>

                    <h6 class="mt-4">Type d'événement</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkWebinar">
                        <label class="form-check-label" for="checkWebinar">Webinars</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkConference">
                        <label class="form-check-label" for="checkConference">Conférences</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkWorkshop">
                        <label class="form-check-label" for="checkWorkshop">Ateliers</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkMedical">
                        <label class="form-check-label" for="checkMedical">Rendez-vous médicaux</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkSport">
                        <label class="form-check-label" for="checkSport">Activités sportives</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des services -->
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucun service disponible dans cette catégorie pour le moment.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
