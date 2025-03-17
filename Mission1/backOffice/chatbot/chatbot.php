<?php
$title = "Gestion du Chatbot";
include_once "../includes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include_once "../includes/sidebar.php"; ?>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestion du Chatbot</h1>
                </div>

                <!-- Formulaire pour ajouter une question -->
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Ajouter une nouvelle question</h5>
                    </div>
                    <div class="card-body">
                        <form id="addQuestionForm" onsubmit="event.preventDefault(); addChatbotQuestion();">
                            <div class="mb-3">
                                <label for="questionInput" class="form-label">Question</label>
                                <input type="text" class="form-control" id="questionInput" required>
                            </div>
                            <div class="mb-3">
                                <label for="answerInput" class="form-label">Réponse</label>
                                <textarea class="form-control" id="answerInput" rows="1" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
                </div>

                <!-- Liste des questions existantes -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Questions du Chatbot</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Question</th>
                                        <th scope="col">Réponse</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="chatbotList">
                                    <tr>
                                        <td colspan="4" class="text-center">Chargement des questions...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal pour modifier une question -->
                <div class="modal fade" id="editChatbotModal" tabindex="-1" aria-labelledby="editChatbotModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editQuestionForm" onsubmit="event.preventDefault(); updateChatbotQuestion();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editChatbotModalLabel">Modifier la question</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editChatbotId">
                                    <div class="mb-3">
                                        <label for="editQuestionInput" class="form-label">Question</label>
                                        <input type="text" class="form-control" id="editQuestionInput" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editAnswerInput" class="form-label">Réponse</label>
                                        <textarea class="form-control" id="editAnswerInput" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchChatbotQuestions();
        });

        function fetchChatbotQuestions() {
            const chatbotList = document.getElementById('chatbotList');
            chatbotList.innerHTML = '<tr><td colspan="4" class="text-center">Chargement des questions...</td></tr>';

            // Appeler l'API pour récupérer toutes les questions
            fetch('../../api/chatbot/getAll.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    chatbotList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.chatbot_id}</td>
                                <td>${item.question || '-'}</td>
                                <td>${item.answer || '-'}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex">
                                        <button class="btn btn-sm btn-outline-secondary me-1"
                                                data-chatbot-id="${item.chatbot_id}"
                                                data-question="${item.question ? item.question.replace(/"/g, '&quot;') : ''}"
                                                data-answer="${item.answer ? item.answer.replace(/"/g, '&quot;') : ''}"
                                                onclick="openEditModalFromButton(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteChatbotQuestion(${item.chatbot_id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            `;
                            chatbotList.appendChild(row);
                        });
                    } else {
                        chatbotList.innerHTML = '<tr><td colspan="4" class="text-center">Aucune question trouvée</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    chatbotList.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Erreur lors du chargement</td></tr>';
                });
        }


        function addChatbotQuestion() {
            const question = document.getElementById('questionInput').value.trim();
            const answer = document.getElementById('answerInput').value.trim();
            if (!question || !answer) return;

            fetch('../../api/chatbot/create.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        question,
                        answer
                    })
                })
                .then(response => {
                    if (response.empty) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.empty) {
                        alert('Question ajoutée avec succès.');
                        document.getElementById('addQuestionForm').reset();
                        fetchChatbotQuestions();
                    } else {
                        alert('Erreur lors de l\'ajout de la question.');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }

        function deleteChatbotQuestion(chatbot_id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette question ?')) {
                fetch('../../api/chatbot/delete.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            chatbot_id
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.empty) {
                            alert('Question supprimée avec succès.');
                            fetchChatbotQuestions();
                        } else {
                            alert('Erreur lors de la suppression de la question.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la suppression.');
                    });
            }
        }

        function openEditModal(questionId, question, reponse) {
            document.getElementById('editChatbotId').value = questionId;
            document.getElementById('editQuestionInput').value = question;
            document.getElementById('editAnswerInput').value = reponse;

            const editModal = new bootstrap.Modal(document.getElementById('editChatbotModal'));
            editModal.show();
        }

        function openEditModalFromButton(button) {
            const chatbotId = button.getAttribute('data-chatbot-id');
            const question = button.getAttribute('data-question');
            const answer = button.getAttribute('data-answer');
            openEditModal(chatbotId, question, answer);
        }

        function updateChatbotQuestion() {
            const chatbot_id = document.getElementById('editChatbotId').value;
            const question = document.getElementById('editQuestionInput').value.trim();
            const answer = document.getElementById('editAnswerInput').value.trim();
            if (!question || !answer) return;

            fetch('../../api/chatbot/update.php', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        chatbot_id,
                        question,
                        answer
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.chatbot_id) {
                        alert('Question mise à jour avec succès.');
                        fetchChatbotQuestions();
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('editChatbotModal'));
                        editModal.hide();
                    } else {
                        alert('Erreur lors de la mise à jour de la question.');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>

    <style>
        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
</body>

</html>
