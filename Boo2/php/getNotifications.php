<?php
require_once 'db_connect.php';
session_start();

$userId = $_SESSION['user_id'];

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT n.notification_id, n.type, n.created_at, 
            CASE 
                WHEN n.type = 'friend_request' THEN (SELECT username FROM users WHERE user_id = (SELECT sender_id FROM friend_requests WHERE notification_id = n.notification_id))
                WHEN n.type = 'friend_accept' THEN (SELECT username FROM users WHERE user_id = (SELECT related_id FROM notifications WHERE notification_id = n.notification_id))
                ELSE '' 
            END AS sender 
        FROM notifications n WHERE n.user_id = ? ORDER BY n.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'notifications' => $notifications]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
