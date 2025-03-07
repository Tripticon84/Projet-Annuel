<?php
$title = "Creation des Administrateurs";
include_once "../includes/head.php";
?>
<body class="container mt-5">
    <a href="admin.php" class="btn btn-secondary mb-3">&larr; Retour</a>  
    <div class="card p-4 shadow-sm">
        <h2 class="text-center mb-4">Créer un Administrateur</h2>
        <form id="adminForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="8">
            </div>

            <button type="submit" class="btn btn-primary w-100">Créer Admin</button>
        </form>
        <p id="responseMessage" class="mt-3 text-center"></p>
    </div>

    <script>
        document.getElementById('adminForm').addEventListener('submit', function(event) {  //Ajoute un écouteur d'événements sur le formulaire qui s'exécute lorsque l'utilisateur le soumet.
            event.preventDefault();  //Empêche le rechargement de la page, qui est le comportement par défaut d'un formulaire HTML.

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const responseMessage = document.getElementById('responseMessage');

            console.log("Envoi des données :", { email, password }); // Log the data being sent

            fetch('../../api/admin/create.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.text()) // Get raw response text
            .then(text => {
                console.log("Réponse brute :", text); // Log raw response text
                try {
                    const data = JSON.parse(text); // Attempt to parse JSON
                    console.log("Réponse JSON :", data); // Log the data object
                    if (data && data.success) {
                        responseMessage.textContent = "Admin créé avec succès. ID: " + data.id;
                        responseMessage.classList.add("text-success");
                        responseMessage.classList.remove("text-danger");
                    } else if (data && data.error) {
                        responseMessage.textContent = "Erreur: " + data.error;
                        responseMessage.classList.add("text-danger");
                        responseMessage.classList.remove("text-success");
                    }
                } catch (error) {
                    console.error("Erreur JSON :", error); // Log JSON parsing error
                    responseMessage.textContent = "Erreur de serveur. Voir la console pour plus de détails.";
                    responseMessage.classList.add("text-danger");
                    responseMessage.classList.remove("text-success");
                }
            })
            .catch(error => {
                console.error("Erreur fetch :", error); // Affiche l'erreur dans la console
                responseMessage.textContent =  error.message;
                responseMessage.classList.add("text-danger");
                responseMessage.classList.remove("text-success");
            });
        });
    </script>
</body>
</html>
