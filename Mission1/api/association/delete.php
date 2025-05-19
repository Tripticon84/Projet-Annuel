<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/dao/association.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/server.php';

header('Content-Type: application/json');

try {
    if (!methodIsAllowed('delete')) {
        returnError(405, 'Method not allowed');
        return;
    }

    // Only admins can delete associations
    try {
        acceptedTokens(true, false, false, false);
    } catch (Exception $e) {
        returnError(401, 'Unauthorized: ' . $e->getMessage());
        return;
    }

    // Get JSON data from request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['association_id'])) {
        returnError(400, 'Association ID is required');
        return;
    }

    $result = deleteAssociation($data['association_id']);

    if ($result === null) {
        returnError(500, 'Failed to delete association');
        return;
    }

    echo json_encode(['success' => true, 'message' => 'Association deleted successfully']);
} catch (Exception $e) {
    returnError(500, 'Server error: ' . $e->getMessage());
}

