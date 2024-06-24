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

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$friend_username = $data['username'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT user_id FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $friend_username, PDO::PARAM_STR);
    $stmt->execute();
    $friend = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$friend) {
        echo json_encode(['success' => false, 'message' => 'Friend not found']);
        exit;
    }

    $friend_id = $friend['user_id'];

    $query = "DELETE FROM friends WHERE user1_id = :user1_id AND user2_id = :user2_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user2_id', $friend_id, PDO::PARAM_INT);
    $stmt->execute();

    $query = "DELETE FROM friends WHERE user1_id = :user2_id AND user2_id = :user1_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user2_id', $friend_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Friend removed']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
