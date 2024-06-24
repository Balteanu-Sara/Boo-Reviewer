<?php
require_once 'db_connect.php';

$bookId = isset($_GET['bookId']) ? $_GET['bookId'] : die();

session_start();
$userId = $_SESSION['user_id'];

$db = new DbConnect();
$conn = $db->getConnection();

$sql = "SELECT review FROM reviews WHERE book_id = :bookId AND user_id = :userId";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $response['userHasReview'] = true;
    $response['userReview'] = $row['review'];
} else {
    $response['userHasReview'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
