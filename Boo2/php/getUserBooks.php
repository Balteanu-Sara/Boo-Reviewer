<?php
require_once 'db_connect.php';

// verificare daca userul este autentficat
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'User not authenticated.'));
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "
        SELECT b.image_url, b.title, ub.user_book_id, b.book_id, b.author, g.name as genre, b.year, b.publisher, ub.status
        FROM user_books ub
        JOIN books b ON ub.book_id = b.book_id
        JOIN genres g ON b.genre_id = g.genre_id
        WHERE ub.user_id = :user_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode(array('success' => true, 'books' => $books));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
}
