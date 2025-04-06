<?php

// Inclure l'en-tête
require_once 'includes/head.php';
require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h1 class="card-title">Signalement anonyme</h1>
                    <p class="card-text">Cet espace vous permet de signaler des situations problématiques en toute confidentialité.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Formulaire de signalement</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de signalement <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Choisir un type</option>
                                <option value="Harcèlement">Harcèlement</option>
                                <option value="Discrimination">Discrimination</option>
                                <option value="Conditions de travail">Conditions de travail</option>
                                <option value="Santé mentale">Santé mentale</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <!-- Champ qui s'affiche uniquement lorsque "Autre" est sélectionné -->
                        <div class="mb-3" id="autreTypeDiv" style="display: none;">
                            <label for="autreType" class="form-label">Précisez le type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="autreType" name="autreType" placeholder="Veuillez préciser le type de signalement...">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description détaillée <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="6" placeholder="Décrivez la situation en détail..." required></textarea>
                            <div class="form-text">Votre description nous aide à comprendre la situation et à prendre les mesures appropriées.</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Tous les signalements sont traités avec la plus grande confidentialité, conformément à notre politique de protection des données.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">Envoyer le signalement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    var autreTypeDiv = document.getElementById('autreTypeDiv');
    if (this.value === 'Autre') {
        autreTypeDiv.style.display = 'block';
        document.getElementById('autreType').required = true;
    } else {
        autreTypeDiv.style.display = 'none';
        document.getElementById('autreType').required = false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>
