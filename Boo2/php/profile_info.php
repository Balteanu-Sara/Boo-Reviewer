<?php
session_start();

require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Trebuie sa te autenfici pentru a accesa profilul!"));
    exit();
}

$user_id = $_SESSION['user_id'];

$db = new DbConnect();
$conn = $db->getConnection();

$query = "SELECT username, email FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);

if ($stmt->execute()) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        http_response_code(200);
        echo json_encode($user);
    } else {
        //  nu s-a gasit utilizatorul in BD
        http_response_code(404);
        echo json_encode(array("message" => "Informațiile utilizatorului nu au fost găsite."));
    }
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Eroare la interogarea bazei de date."));
}
