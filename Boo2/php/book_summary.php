<?php
require_once 'db_connect.php';

if (!isset($_GET['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'No book ID provided!']);
    exit;
}

$bookId = $_GET['book_id'];

$db = new DbConnect();
$conn = $db->getConnection();

$sql = "SELECT books.title, books.author, books.publisher, books.year, books.image_url, genres.name AS genre, books.summary FROM books LEFT JOIN genres ON books.genre_id = genres.genre_id WHERE books.book_id = :book_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
$stmt->execute();

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo json_encode(['success' => false, 'message' => 'Book not found!']);
    exit;
}

echo json_encode(['success' => true, 'book' => $book]);
