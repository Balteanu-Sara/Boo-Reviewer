<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

function getGenreDescription($genre)
{
    $url = "https://www.goodreads.com/genres/$genre";
    $html = file_get_contents($url);

    if ($html === FALSE) {
        return "Description not available1.";
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $description = $xpath->query("//div[@class='leftContainer']/div[@class='mediumText reviewText']");

    if ($description->length > 0) {
        return trim($description->item(0)->textContent);
    } else {
        return "Description not available right now.";
    }
}

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $user_id = $_SESSION['user_id'];

    $query = "SELECT g.name FROM genres g JOIN user_genres ug ON g.genre_id = ug.genre_id WHERE ug.user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $descriptions = [];
    foreach ($genres as $genre) {
        $descriptions[$genre['name']] = getGenreDescription($genre['name']);
    }

    echo json_encode(['success' => true, 'descriptions' => $descriptions]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
