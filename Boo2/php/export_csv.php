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
                b.book_id AS BookId,
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

    if ($stmt->rowCount() > 0) {
        $filename = 'export_user_books.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        fputcsv($output, array('Book ID', 'Title', 'Author', 'Genre', 'Year', 'Publisher', 'Status', 'Review'));

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, array(
                $row['BookId'],
                $row['Title'],
                $row['Author'],
                $row['Genre'],
                $row['Year'],
                $row['Publisher'],
                $row['Status'],
                $row['Review']
            ));
        }

        fclose($output);
        exit;
    } else {
        http_response_code(404);
        die('No data found for export');
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Error exporting CSV: ' . $e->getMessage();
}
