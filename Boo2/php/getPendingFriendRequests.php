<?php
require_once 'db_connect.php';
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'User not authenticated'));
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $sql = "SELECT 
    fr.request_id,
    sender.username AS from_user,
    receiver.username AS to_user
FROM 
    friend_requests fr
INNER JOIN 
    users sender ON fr.sender_id = sender.user_id
INNER JOIN
    users receiver ON fr.receiver_id = receiver.user_id
WHERE 
    (fr.sender_id = :user_id OR fr.receiver_id = :user_id)
    AND fr.status = 'pending'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = array();
    $response['success'] = true;
    $response['requests'] = $pendingRequests;

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'message' => 'Error fetching pending friend requests: ' . $e->getMessage()));
}
