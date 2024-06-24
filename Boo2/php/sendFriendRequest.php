<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$sender_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$receiver_username = $data['username'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT user_id FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $receiver_username, PDO::PARAM_STR);
    $stmt->execute();
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receiver) {
        echo json_encode(['success' => false, 'message' => 'Username not found']);
        exit;
    }

    $receiver_id = $receiver['user_id'];

    $query = "INSERT INTO notifications (user_id, related_id, type) VALUES (:user_id, :related_id, 'friend_request')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $receiver_id, PDO::PARAM_INT);
    $stmt->bindParam(':related_id', $sender_id, PDO::PARAM_INT);
    $stmt->execute();
    $notification_id = $conn->lastInsertId();

    $query = "INSERT INTO friend_requests (notification_id, sender_id, receiver_id, status) VALUES (:notification_id, :sender_id, :receiver_id, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':notification_id', $notification_id, PDO::PARAM_INT);
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Friend request sent.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
