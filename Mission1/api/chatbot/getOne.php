<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chatbot.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

// // Vérification du token d'authentification
// if (!isset($_GET['token'])) {
//     returnError(401, 'Token not provided');
//     return;
// }
// tokenVerification($_GET['token']);

// Vérification de l'ID du chatbot
if (!isset($_GET['chatbot_id']) || empty($_GET['chatbot_id'])) {
    returnError(400, 'chatbot_id  not provided');
    return;
}

$chatbotId = intval($_GET['chatbot_id']);
$chatbot = getChatbot($chatbotId);

if (!$chatbot) {
    returnError(404, 'chatbot not found');
    return;
}

$result = [
    "chatbot_id" => $chatbot['question_id'],
    "question" => $chatbot['question'],
    "answer" => $chatbot['reponse'],
];

if (empty($result)) {
    returnError(404, 'No chatbot found');
    return;
}

echo json_encode($result);
http_response_code(200);
