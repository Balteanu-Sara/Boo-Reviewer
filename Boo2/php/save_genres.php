<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(array("message" => "Utilizatorul nu este autentificat."));
        exit();
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->genres) || !is_array($data->genres)) {
        http_response_code(400);
        echo json_encode(array("message" => "Genurile nu au fost transmise corect!"));
        exit();
    }

    $db = new DbConnect();
    $conn = $db->getConnection();

    foreach ($data->genres as $genreName) {
        $query = "SELECT genre_id FROM genres WHERE name = :name";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $genreName);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $insertQuery = "INSERT INTO genres (name) VALUES (:name)";
            $stmtInsert = $conn->prepare($insertQuery);
            $stmtInsert->bindParam(':name', $genreName);
            $stmtInsert->execute();
        }

        $genreId = $row['genre_id'] ?? $conn->lastInsertId();

        $insertUserGenreQuery = "INSERT INTO user_genres (user_id, genre_id) VALUES (:user_id, :genre_id)";
        $stmtUserGenre = $conn->prepare($insertUserGenreQuery);
        $stmtUserGenre->bindParam(':user_id', $_SESSION['user_id']);
        $stmtUserGenre->bindParam(':genre_id', $genreId);
        $stmtUserGenre->execute();
    }

    http_response_code(200);
    echo json_encode(array("success" => true, "message" => "Genurile au fost salvate cu succes."));
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Metoda HTTP nu este permisa pentru aceasta ruta."));
}
