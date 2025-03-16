<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chatbot.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('create')) {
    returnError(405, 'Method not allowed');
    return;
}

$data = getBody();
$question = $data['question'];
$answer = $data['answer'];

if (validateMandatoryParams($data, ['question', 'answer'])) {
    $newChatbotId = createChatbot($question, $answer);

    if (!$newChatbotId) {
        // Log the error for debugging
        error_log("Failed to create chatbot: " . print_r(error_get_last(), true));
        returnError(500, 'Could not create the Chatbot. Database operation failed.');
        return;
    }

    echo json_encode(['chatbot_id' => $newChatbotId]);
    http_response_code(201);
}else{
    returnError(400, 'Missing required parameters');
    return;
}
