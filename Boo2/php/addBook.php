<?php
require_once 'db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Nu esti autentificat!'));
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['book_id']) && is_numeric($data['book_id'])) {
    $book_id = $data['book_id'];

    try {
        $db = new DbConnect();
        $conn = $db->getConnection();

        // verificare daca cartea se afla deja in colectia utilizatorului
        $query = "SELECT * FROM user_books WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO user_books (user_id, book_id) VALUES (:user_id, :book_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmt->execute();

            http_response_code(200);
            echo json_encode(array('success' => true));
        } else if ($stmt->rowCount() > 0) {
            http_response_code(409); // Conflict
            echo json_encode(array('success' => false, 'message' => 'Cartea este deja adaugata in colectia ta!'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid book ID.'));
}
