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

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT u.username 
        FROM users u
        JOIN (
            SELECT user_id, COUNT(*) AS genre_count 
            FROM user_genres 
            WHERE genre_id IN (SELECT genre_id FROM user_genres WHERE user_id = :user_id)
            AND user_id != :user_id 
            GROUP BY user_id 
            HAVING genre_count >= 2
        ) ug ON u.user_id = ug.user_id
        WHERE u.user_id NOT IN (
            SELECT receiver_id FROM friend_requests WHERE sender_id = :user_id
            UNION
            SELECT sender_id FROM friend_requests WHERE receiver_id = :user_id
            UNION
            select user2_id FROM friends where user1_id = :user_id
            )";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        throw new Exception('Failed to prepare statement: ' . $conn->$error);
    }

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $suggestedPersons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($suggestedPersons === false) {
        throw new Exception('Failed to fetch results: ' . $stmt->errorInfo()[2]);
    }

    http_response_code(200);
    echo json_encode(['success' => true, 'suggestedPersons' => array_column($suggestedPersons, 'username')]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
