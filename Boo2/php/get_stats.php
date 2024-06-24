<?php
require_once 'db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Nu esti autentificat!'));
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $sql = "SELECT 
                COUNT(user_book_id) AS total_books,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) AS total_progress
            FROM user_books
            WHERE user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $statistics = array(
            'total_books' => $row['total_books'],
            'total_progress' => $row['total_progress']
        );
        echo json_encode($statistics);
    } else {
        echo json_encode(array());
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Error fetching user statistics: ' . $e->getMessage();
}
