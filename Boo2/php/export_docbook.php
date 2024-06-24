<?php
require_once 'db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'Nu esti autentificat!'));
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    $sql = "SELECT 
                b.title AS Title,
                b.author AS Author,
                g.name AS Genre,
                b.year AS Year,
                b.publisher AS Publisher,
                ub.status AS Status,
                r.review AS Review
            FROM 
                user_books ub
            JOIN 
                books b ON ub.book_id = b.book_id
            JOIN 
                genres g ON b.genre_id = g.genre_id
            LEFT JOIN 
                reviews r ON ub.book_id = r.book_id AND ub.user_id = r.user_id
            WHERE 
                ub.user_id = :userId";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) > 0) {
        $filename = 'export_user_books.xml';
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $docbook = '<?xml version="1.0" encoding="UTF-8"?>';
        $docbook .= '<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.5//EN" "http://www.oasis-open.org/docbook/xml/4.5/docbookx.dtd">';
        $docbook .= '<book>';
        $docbook .= '<title>User Books</title>';

        foreach ($books as $book) {
            $docbook .= '<chapter>';
            $docbook .= '<title>' . htmlspecialchars($book['Title']) . '</title>';
            $docbook .= '<author>' . htmlspecialchars($book['Author']) . '</author>';
            $docbook .= '<genre>' . htmlspecialchars($book['Genre']) . '</genre>';
            $docbook .= '<year>' . htmlspecialchars($book['Year']) . '</year>';
            $docbook .= '<publisher>' . htmlspecialchars($book['Publisher']) . '</publisher>';
            $docbook .= '<status>' . htmlspecialchars($book['Status']) . '</status>';
            $docbook .= '<review>' . htmlspecialchars($book['Review']) . '</review>';
            $docbook .= '</chapter>';
        }

        $docbook .= '</book>';

        echo $docbook;
        exit;
    } else {
        http_response_code(404);
        die('No data found for export');
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Error exporting DocBook: ' . $e->getMessage();
}
