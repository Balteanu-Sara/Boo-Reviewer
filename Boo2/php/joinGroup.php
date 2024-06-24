<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}
$userId = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$group_id = isset($data['group_id']) ? intval($data['group_id']) : null;

if ($userId === null || $group_id === null) {
    http_response_code(400);
    echo json_encode(['message' => 'Bad Request']);
    exit();
}

$db = new DbConnect();
$conn = $db->getConnection();

// verificam daca userul face deja parte din acest grup
$stmt = $conn->prepare('SELECT * FROM user_groups WHERE user_id = :user_id AND group_id = :group_id');
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(['message' => 'User already in the group']);
    exit();
}

// adaugam userul in grup
$stmt = $conn->prepare('INSERT INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)');
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['message' => 'User added to the group']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error']);
}
