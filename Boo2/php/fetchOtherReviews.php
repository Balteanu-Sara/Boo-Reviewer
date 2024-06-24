<?php
require_once 'db_connect.php';

$bookId = isset($_GET['bookId']) ? $_GET['bookId'] : die();

$db = new DbConnect();
$conn = $db->getConnection();

$sql = "SELECT reviews.review, users.username
        FROM reviews
        INNER JOIN users ON reviews.user_id = users.user_id
        WHERE reviews.book_id = :bookId AND reviews.user_id != :userId";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);

session_start();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

$stmt->execute();
$otherReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response['otherReviews'] = $otherReviews;

if (count($otherReviews) === 0) {
    $response['noReviews'] = true;
} else {
    $response['noReviews'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
