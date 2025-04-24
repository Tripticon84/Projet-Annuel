<?php
$title = "Associations";

require_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/employee/includes/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/employee/includes/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';

$collaborateurId = $_SESSION['collaborateur_id'];
$associations = getAllAssociations();
$myAssociations = getEmployeeAssociations($collaborateurId);

// Créer un tableau des IDs des associations auxquelles le collaborateur participe
$participatingIds = array_map(function($assoc) {
    return $assoc['association_id'];
}, $myAssociations ?: []);

?>

<div class="container mt-4">
    <h2>Associations</h2>

    <div class="row" id="associations-list">
        <?php if (empty($associations)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune association n'est disponible pour le moment.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($associations as $association): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <?php if ($association['banniere']): ?>
                            <img src="/uploads/associations/<?= htmlspecialchars($association['banniere']) ?>"
                                 class="card-img-top" alt="Bannière de l'association">
                        <?php endif; ?>

                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($association['logo']): ?>
                                    <img src="/uploads/associations/<?= htmlspecialchars($association['logo']) ?>"
                                         class="me-2" alt="Logo" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php endif; ?>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($association['name']) ?></h5>
                            </div>

                            <p class="card-text"><?= htmlspecialchars($association['description']) ?></p>
                            <p class="text-muted">
                                <small>Créée le: <?= date('d/m/Y', strtotime($association['date_creation'])) ?></small>
                            </p>
                        </div>

                        <div class="card-footer bg-transparent">
                            <?php if (in_array($association['association_id'], $participatingIds)): ?>
                                <button class="btn btn-danger btn-sm float-end"
                                        onclick="unsubscribeFromAssociation(<?= $collaborateurId ?>, <?= $association['association_id'] ?>)">
                                    Se désinscrire
                                </button>
                                <span class="badge bg-success me-2">Membre</span>
                            <?php else: ?>
                                <button class="btn btn-primary btn-sm float-end"
                                        onclick="subscribeToAssociation(<?= $collaborateurId ?>, <?= $association['association_id'] ?>)">
                                    Rejoindre
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="/data/static/js/employee.js"></script>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/employee/includes/footer.php'; ?>
