<?php
// Inclure l'en-tête
require_once 'includes/head.php';
include_once 'includes/header.php';
?>

<div class="container mt-4">
    <!-- Titre de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h1 class="card-title">Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?> !</h1>
                    <p class="card-text">Votre espace personnel pour découvrir les services Business Care.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne de gauche : Événements à venir et Activités récentes -->
        <div class="col-md-8">
            <!-- Événements à venir -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar"></i> Événements à venir</h5>
                </div>
                <div class="card-body">
                    <?php
                    require_once '../../api/dao/event.php';
                    
                    $events = getAllEvents(3); // Récupérer les 3 prochains événements
                    
                    if ($events && count($events) > 0) {
                        foreach ($events as $event) {
                            ?>
                            <div class="mb-2 p-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><?php echo htmlspecialchars($event['nom']); ?></strong>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($event['date'])); ?></small>
                                </div>
                                <?php if (!empty($event['lieu'])) { ?>
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['lieu']); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="text-muted">Aucun événement à venir pour le moment.</p>';
                    }
                    ?>
                    <div class="mt-3">
                        <a href="catalogue.php" class="btn btn-outline-primary">Voir tous les événements</a>
                    </div>
                </div>
            </div>

            <!-- Mes réservations -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-bookmark"></i> Mes réservations</h5>
                </div>
                <div class="card-body">
                    <div id="my-activities">
                        <p class="text-muted">Chargement des réservations...</p>
                    </div>
                    <div class="mt-3">
                        <a href="planning.php" class="btn btn-outline-primary">Voir mon planning</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite : Accès rapides et Conseils -->
        <div class="col-md-4">
            <!-- Accès rapides -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-bolt"></i> Accès rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="catalogue.php" class="btn btn-primary">
                            <i class="fas fa-book-open"></i> Catalogue de services
                        </a>
                        <a href="chatbot.php" class="btn btn-info text-white">
                            <i class="fas fa-robot"></i> Assistance Chatbot
                        </a>
                        <a href="signalement.php" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle"></i> Signalement anonyme
                        </a>
                        <a href="conseils.php" class="btn btn-success">
                            <i class="fas fa-lightbulb"></i> Conseils bien-être
                        </a>
                    </div>
                </div>
            </div>

            <!-- Conseils hebdomadaires -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-lightbulb"></i> Conseils de la semaine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Aucun conseil disponible pour le moment.</p>
                </div>
            </div>

            <!-- Communautés actives -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-users"></i> Communautés actives</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sport & Bien-être
                            <span class="badge bg-primary rounded-pill">14</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Lecture & Culture
                            <span class="badge bg-primary rounded-pill">8</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Actions solidaires
                            <span class="badge bg-primary rounded-pill">3</span>
                        </li>
                    </ul>
                    <a href="communautes.php" class="btn btn-outline-primary btn-sm mt-3">Rejoindre une communauté</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ajout du script pour l'ID du collaborateur -->
<script>
    const collaborateurId = <?php echo isset($_SESSION['collaborateur_id']) ? $_SESSION['collaborateur_id'] : 'null'; ?>;
</script>
<script src="/data/static/js/employee.js"></script>

<?php
// Inclusion du pied de page
include 'includes/footer.php';
?>
