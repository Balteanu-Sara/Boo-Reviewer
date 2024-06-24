<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Method not allowed';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['bookId']) || !isset($data['review'])) {
    $response['success'] = false;
    $response['message'] = 'Invalid data received';
    echo json_encode($response);
    exit;
}

session_start();
$userId = $_SESSION['user_id'];

$db = new DbConnect();
$conn = $db->getConnection();

$bookId = $data['bookId'];
$review = $data['review'];

$sqlCheck = "SELECT review_id FROM reviews WHERE book_id = :bookId AND user_id = :userId";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindParam(':bookId', $bookId, PDO::PARAM_INT);
$stmtCheck->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCheck->execute();

if ($stmtCheck->rowCount() > 0) {
    $sqlUpdate = "UPDATE reviews SET review = :review WHERE book_id = :bookId AND user_id = :userId";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':review', $review, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':bookId', $bookId, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt = $stmtUpdate;
} else {
    // adaugam recenzia in tabel
    $sqlInsert = "INSERT INTO reviews (review, book_id, user_id) VALUES (:review, :bookId, :userId)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':review', $review, PDO::PARAM_STR);
    $stmtInsert->bindParam(':bookId', $bookId, PDO::PARAM_INT);
    $stmtInsert->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt = $stmtInsert;
}

$response = [];

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = 'Error posting review';
}

header('Content-Type: application/json');
echo json_encode($response);
