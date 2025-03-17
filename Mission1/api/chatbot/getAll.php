<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/chatbot.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

if (!methodIsAllowed('read')) {
    returnError(405, 'Method not allowed');
    return;
}

acceptedTokens(true, false, false, false);


$limit = null;
$offset = null;
$chatbot_id = null;


if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
    if ($limit < 1) {
        returnError(400, 'Limit must be a positive and non zero number');
    }
}
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    if ($offset < 0) {
        returnError(400, 'Offset must be a positive number');
    }
}

$chatbots = getAllChatbots($limit, $offset);

if (!$chatbots) {
    returnError(404, 'No company found');
    return;
}

$result = []; // Initialize the result array

foreach ($chatbots as $chatbot) {
    $result[] = [
        "chatbot_id" => $chatbot['question_id'],
        "question" => $chatbot['question'],
        "answer" => $chatbot['reponse'],
    ];
}

if (empty($result)) {
    returnError(404, 'No chatbot found');
    return;
}

echo json_encode($result);
http_response_code(200);
