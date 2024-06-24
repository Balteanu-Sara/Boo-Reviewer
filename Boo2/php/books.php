<?php
require_once 'db_connect.php';

// preluarea cartilor din baza de date pentru a le afisa
if (isset($_GET['genre']) && is_numeric($_GET['genre'])) {
    $genreId = $_GET['genre'];

    try {
        $db = new DbConnect();
        $conn = $db->getConnection();

        $query = "SELECT image_url, title, author, book_id FROM books WHERE genre_id = :genreId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':genreId', $genreId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200); // ok
            echo json_encode($books);
        } else {
            http_response_code(404); // not found
            echo json_encode(array("message" => "Nu s-au gasit carti pentru genul specificat."));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Eroare la conectarea la baza de date: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Parametrul 'genre' lipsește sau nu este valid."));
}
