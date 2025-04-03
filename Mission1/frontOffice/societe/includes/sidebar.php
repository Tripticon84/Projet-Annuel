<?php
$current_page = basename($_SERVER['SCRIPT_NAME']); ?>

<style>
    .sidebar {
        min-height: 100vh;
        background-color: var(--dark-color, #212529);
        color: white;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        border-radius: 0.25rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link i {
        margin-right: 0.5rem;
        width: 1.5rem;
        text-align: center;
    }
</style>

<!-- Sidebar -->
<div id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3 d-flex flex-column" style="height: 100vh;">
        <div class="text-center mb-4">
            <h3>Business Care</h3>
            <h6>Espace Société</h6>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'home.php') ? 'active' : '' ?>" href="/frontOffice/societe/home.php">
                    <i class="fas fa-home"></i> Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'estimates.php') ? 'active' : '' ?>" href="/frontOffice/societe/estimates/estimates.php">
                    <i class="fas fa-file-invoice"></i> Devis
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'contracts.php') ? 'active' : '' ?>" href="/frontOffice/societe/contracts/contracts.php">
                    <i class="fas fa-file-contract"></i> Contrats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'invoices.php') ? 'active' : '' ?>" href="/frontOffice/societe/invoices/invoices.php">
                    <i class="fas fa-file-invoice-dollar"></i> Factures
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'costs.php') ? 'active' : '' ?>" href="/frontOffice/societe/other_costs/other_cost.php">
                    <i class="fas fa-receipt"></i> Autres frais
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'employees.php') ? 'active' : '' ?>" href="/frontOffice/societe/employees/employees.php">
                    <i class="fas fa-users"></i> Collaborateurs
                </a>
            </li>
        </ul>
        <ul class="nav flex-column mt-auto">
            <li class="nav-item">
                <a class="nav-link text-danger" href="/frontOffice/societe/login/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>
</div>
