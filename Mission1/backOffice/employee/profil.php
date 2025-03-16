<?php
$title = "Profil de l'employé";
include_once "../includes/head.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/employee.php';

// Vérifier si l'ID est fourni dans l'URL
$employeeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($employeeId <= 0) {
    // Rediriger si l'ID n'est pas valide
    header('Location: /backOffice/employee/employee.php');
    exit;
}

// Récupérer les informations de l'employé
$employee = getEmployeeProfile($employeeId);

if (!$employee) {
    // Rediriger si l'employé n'existe pas
    header('Location: /backOffice/employee/employee.php');
    exit;
}
?>

<body class="container mt-5">
    <a href="employee.php" class="btn btn-secondary mb-3">&larr; Retour</a>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Profil de l'employé</h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['nom']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Prénom</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['prenom']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Rôle</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['role']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['email']); ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Téléphone</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['telephone']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Date de création</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['date_creation']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Dernière activité</label>
                    <p class="form-control-static"><?php echo htmlspecialchars($employee['date_activite']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
