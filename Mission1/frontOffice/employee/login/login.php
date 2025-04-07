<?php
$title = "Connexion - Espace Salarié";

include $_SERVER['DOCUMENT_ROOT'] . "/frontOffice/employee/includes/head.php";

// Récupérer le message d'erreur s'il existe
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">Espace Salarié</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="/data/static/logo.png" alt="Business Care Logo" class="img-fluid" style="max-height: 150px;">
                        <h4 class="mt-3">Connexion à votre espace personnel</h4>
                    </div>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="post" action="login_process.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Connexion</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="forgot_password.php">Mot de passe oublié ?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
