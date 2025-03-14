            <?php

            $current_page = basename($_SERVER['SCRIPT_NAME']); ?>

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h3>Business Care</h3>
                        <p class="text-muted">Administration</p>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'home.php') ? 'active' : '' ?>" href="/backOffice/home.php">
                                <i class="fas fa-home"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'society.php') ? 'active' : '' ?>" href="/backOffice/society/society.php">
                                <i class="fas fa-building"></i> Sociétés clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'contract.php') ? 'active' : '' ?>" href="/backOffice/contract/contract.php">
                                <i class="fas fa-file-contract"></i> Contrats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'employee.php') ? 'active' : '' ?>" href="/backOffice/employee/employee.php">
                                <i class="fas fa-users"></i> Collaborateurs clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'provider.php') ? 'active' : '' ?>" href="/backOffice/provider/provider.php">
                                <i class="fas fa-user-tie"></i> Prestataires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'event.php') ? 'active' : '' ?>" href="/backOffice/event/event.php">
                                <i class="fas fa-calendar-alt"></i> Événements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'catalogue.php') ? 'active' : '' ?>" href="/backOffice/catalogue/catalogue.php">
                                <i class="fas fa-book"></i> Catalogue services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page == 'admin.php') ? 'active' : '' ?>" href="/backOffice/admin/admin.php">
                                <i class="fas fa-home"></i> Gestion Admins
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
