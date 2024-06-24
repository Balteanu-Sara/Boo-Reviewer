<?php
session_start();
require_once 'db_connect.php';

if (!isset($_GET['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'No book ID provided!']);
    exit;
}

$bookId = $_GET['book_id'];
$currentUserId = $_SESSION['user_id'];

$db = new DbConnect();
$conn = $db->getConnection();

$sql = "SELECT COUNT(*) AS count FROM user_books WHERE user_id = :user_id AND book_id = :book_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
$stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['count'] > 0) {
    echo json_encode(['success' => true, 'hasBook' => true]);
} else {
    echo json_encode(['success' => true, 'hasBook' => false]);
}
