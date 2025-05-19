<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/frontOffice/societe/includes/head.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['societe_id'])) {
    header('Location: ../../login.php');
    exit;
}

// Récupérer l'ID du devis créé
$estimate_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($estimate_id <= 0) {
    header('Location: list_estimates.php?error=invalid_id');
    exit;
}
?>

<style>
    /* Styles personnalisés pour la page de confirmation */
    .confirmation-container {
        animation: fadeIn 0.5s ease-in-out;
        padding-bottom: 2rem;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .confirmation-alert {
        border-left: 5px solid #198754;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 1rem;
    }
    
    .reference-number {
        font-size: 1.2rem;
        font-weight: 600;
        color: #0d6efd;
        padding: 0.2rem 0.5rem;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 0.25rem;
    }
    
    .summary-card {
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    
    .card-header-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: white;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem 0.5rem 0 0;
        font-weight: 500;
    }
    
    .card-header-info {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
        color: white;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem 0.5rem 0 0;
        font-weight: 500;
    }
    
    .info-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .price-highlight {
        font-size: 1.5rem;
        font-weight: 700;
        color: #198754;
        text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .action-buttons {
        margin-top: 1.5rem;
    }
    
    .action-btn {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s;
        border-radius: 0.375rem;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .step-item {
        border-left: 2px solid #dee2e6;
        padding-left: 1.5rem;
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .step-item:last-child {
        margin-bottom: 0;
    }
    
    .step-item:before {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: white;
        border: 2px solid;
        left: -8px;
        top: 5px;
    }
    
    .step-item.step-warning:before {
        border-color: #ffc107;
    }
    
    .step-item.step-primary:before {
        border-color: #0d6efd;
    }
    
    .step-item.step-success:before {
        border-color: #198754;
    }
    
    .step-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    hr {
        margin: 1.5rem 0;
        opacity: 0.1;
    }
    
    .pdf-btn {
        margin-top: 1rem;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <?php include_once('../../includes/sidebar.php'); ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 confirmation-container">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-check-circle text-success me-2"></i> Confirmation du Devis</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="estimates.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>

            <div class="alert alert-success confirmation-alert p-4 mb-4" role="alert">
                <h4 class="alert-heading mb-2"><i class="fas fa-check-circle me-2"></i> Devis créé avec succès!</h4>
                <p class="mb-0">Votre devis a été créé et envoyé avec succès. Le numéro de référence est: <span class="reference-number">#<?php echo $estimate_id; ?></span></p>
            </div>
            
            <div class="row mb-4 g-4">
                <div class="col-lg-6">
                    <div class="card summary-card">
                        <div class="card-header card-header-primary">
                            <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i> Récapitulatif du Devis</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-line">
                                <span class="info-label">Référence:</span>
                                <span class="badge bg-light text-dark border">#<?php echo $estimate_id; ?></span>
                            </div>
                            
                            <div class="info-line">
                                <span class="info-label">Date de création:</span>
                                <span><?php echo date('d/m/Y'); ?></span>
                            </div>
                            
                            <div class="info-line">
                                <span class="info-label">Statut:</span>
                                <span class="badge bg-info px-3 py-2">
                                    <i class="fas fa-paper-plane me-1"></i> Envoyé
                                </span>
                            </div>
                            
                            <hr>
                            
                            <div class="info-line">
                                <span class="info-label"><i class="far fa-calendar-alt me-2"></i>Période:</span>
                                <span>
                                    Du <?php echo date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-d'))); ?> 
                                    <br class="d-md-none">au <?php echo date('d/m/Y', strtotime($_GET['end_date'] ?? '')); ?>
                                </span>
                            </div>
                            
                            <div class="info-line">
                                <span class="info-label"><i class="fas fa-user-plus me-2"></i>Employés supplémentaires:</span>
                                <span class="badge bg-primary px-3 py-2">
                                    <?php echo isset($_GET['additional_employees']) ? $_GET['additional_employees'] : '0'; ?>
                                </span>
                            </div>
                            
                            <hr>
                            
                            <div class="alert alert-success border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Montant à payer (au prorata):</strong> 
                                    <span class="price-highlight">
                                        <?php echo isset($_GET['prorated_cost']) ? number_format(floatval($_GET['prorated_cost']), 2, ',', ' ') : '0,00'; ?> €
                                    </span>
                                </div>
                                <small class="d-block mt-2">Pour les employés supplémentaires</small>
                            </div>
                            
                            <div class="d-flex align-items-center text-muted mt-3">
                                <i class="fas fa-envelope me-2"></i>
                                <small>Un email de confirmation a été envoyé à l'adresse associée à votre compte.</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card summary-card">
                        <div class="card-header card-header-info">
                            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Prochaines étapes</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Votre demande d'ajout d'employés sera traitée prochainement par notre équipe.</p>
                            
                            <div class="step-item step-warning">
                                <div class="step-title"><i class="fas fa-clock text-warning me-2"></i>En attente de validation</div>
                                <p class="text-muted small mb-0">Notre équipe examinera votre demande sous 24-48h</p>
                            </div>
                            
                            <div class="step-item step-primary">
                                <div class="step-title"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Traitement du paiement</div>
                                <p class="text-muted small mb-0">Un lien de paiement vous sera envoyé après validation</p>
                            </div>
                            
                            <div class="step-item step-success">
                                <div class="step-title"><i class="fas fa-check-circle text-success me-2"></i>Mise à jour de votre abonnement</div>
                                <p class="text-muted small mb-0">Votre nombre d'employés sera mis à jour après paiement</p>
                            </div>
                            
                            <?php if (isset($_GET['pdf_path'])): ?>
                            <a href="<?php echo $_GET['pdf_path']; ?>" class="btn btn-outline-primary w-100 mt-4 pdf-btn" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i> Télécharger le PDF du devis
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row action-buttons g-4">
                <div class="col-lg-6">
                    <a href="../home.php" class="btn btn-primary w-100 action-btn">
                        <i class="fas fa-home me-2"></i> Retour au Tableau de Bord
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="estimates.php" class="btn btn-outline-secondary w-100 action-btn">
                        <i class="fas fa-list me-2"></i> Voir tous mes devis
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>