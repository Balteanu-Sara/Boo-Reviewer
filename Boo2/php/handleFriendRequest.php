<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$notificationId = $data['notificationId'];
$action = $data['action'];
$userId = $_SESSION['user_id'];

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT sender_id, receiver_id FROM friend_requests WHERE notification_id = :notification_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['notification_id' => $notificationId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Invalid friend request']);
        exit;
    }

    $senderId = $request['sender_id'];
    $receiverId = $request['receiver_id'];


    if ($action === 'accept') {
        $query = "DELETE FROM  friend_requests WHERE notification_id = :notification_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['notification_id' => $notificationId]);

        $query = "INSERT INTO friends (user1_id, user2_id) VALUES (:user1_id, :user2_id), (:user2_id, :user1_id)";
        $stmt = $conn->prepare($query);
        $stmt->execute(['user1_id' => $senderId, 'user2_id' => $receiverId]);

        $query = "INSERT INTO notifications (user_id, related_id, type) VALUES (:user_id, :related_id, 'friend_accept')";
        $stmt = $conn->prepare($query);
        $stmt->execute(['user_id' => $senderId, 'related_id' => $receiverId]);

        $query = "DELETE FROM notifications WHERE notification_id = :notification_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['notification_id' => $notificationId]);

        echo json_encode(['success' => true, 'message' => 'Friend request accepted.']);
    } else if ($action === 'remove') {
        $query = "DELETE FROM friend_requests WHERE notification_id = :notification_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['notification_id' => $notificationId]);

        $query = "DELETE FROM notifications WHERE notification_id = :notification_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['notification_id' => $notificationId]);

        echo json_encode(['success' => true, 'message' => 'Friend request removed.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
