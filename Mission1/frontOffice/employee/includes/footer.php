    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Business Care</h5>
                    <p>Une solution qui améliore la santé, le bien-être et la cohésion en milieu professionnel.</p>
                </div>
                <div class="col-md-3">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">À propos</a></li>
                        <li><a href="#" class="text-white">Nos prestations</a></li>
                        <li><a href="#" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <address>
                        <p>110, rue de Rivoli<br>75001 Paris</p>
                        <p>Email: contact@business-care.fr</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Business Care. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="../assets/js/scripts.js"></script>

    <!-- Script pour le tutorial interactif et les notifications -->
    <script>
        $(document).ready(function() {
            // Vérifier si c'est la première connexion (à implémenter côté serveur)
            const isFirstLogin = localStorage.getItem('firstLogin') !== 'false';

            if (isFirstLogin && window.location.pathname.endsWith('index.php')) {
                // Afficher le tutoriel à la première connexion
                startTutorial();
                localStorage.setItem('firstLogin', 'false');
            }

            // Fonction pour le tutoriel
            function startTutorial() {
                // Créer les étapes du tutoriel
                const steps = [
                    {
                        element: '.navbar-brand',
                        title: 'Bienvenue dans votre espace salarié',
                        content: 'Découvrez toutes les fonctionnalités pour améliorer votre bien-être au travail.',
                        position: 'bottom'
                    },
                    // Ajouter d'autres étapes selon les éléments à présenter
                ];

                // Implémenter l'affichage du tutoriel (peut utiliser une bibliothèque comme Intro.js)
                // Code à compléter
            }
        });
    </script>
</body>
</html>
