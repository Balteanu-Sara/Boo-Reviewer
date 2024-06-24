<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array('success' => false, 'message' => 'Unknown error');

require_once 'db_connect.php';
$db = new DbConnect();
$conn = $db->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_book_id']) && isset($data['status'])) {
    $user_book_id = $data['user_book_id'];
    $status = $data['status'];

    try {
        $stmt = $conn->prepare("UPDATE user_books SET status = :status WHERE user_book_id = :user_book_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':user_book_id', $user_book_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Statusul a fost actualizat cu succes!';
        } else {
            $response['message'] = 'Nu s-a putut actualiza statusul!';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Input invalid.';
}

echo json_encode($response);
