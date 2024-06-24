<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/rss+xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<rss version="2.0">';
    echo '<channel>';
    echo '<title>Mai multe informatii</title>';
    echo '<link>http://localhost/Boo2/loggedPage.html</link>';
    echo '<description>Informatii de interes pentru comunitatea noastra</description>';

    require_once 'db_connect.php';
    $db = new DbConnect();
    $conn = $db->getConnection();

    $currentUserId = $_SESSION['user_id'];

    $stmtBooks = $conn->prepare('
    SELECT DISTINCT b.book_id, b.title, b.author, b.published_date
    FROM books b
    INNER JOIN genres g ON b.genre_id = g.genre_id
    INNER JOIN user_genres ug ON g.genre_id = ug.genre_id
    WHERE ug.user_id = :currentUserId
    ORDER BY b.published_date DESC
    LIMIT 4
    ');

    $stmtBooks->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
    $stmtBooks->execute();
    $books = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);

    foreach ($books as $book) {
        $bookId = $book['book_id'];
        $title = htmlspecialchars($book['title']);
        $author = htmlspecialchars($book['author']);
        $publishedDate = date('D, d M Y H:i:s', strtotime($book['published_date']));
        $link = "http://localhost/Boo2/bookSummary.html?book_id=$bookId";
        $description = htmlspecialchars("A aparut un nou volum de interes: \"$title\" scris de $author.");

        echo '<item>';
        echo "<title>A aparut un nou volum de interes: \"$title\"</title>";
        echo "<link>$link</link>";
        echo "<description>$description</description>";
        echo "<pubDate>$publishedDate</pubDate>";
        echo '</item>';
    }

    echo '</channel>';
    echo '</rss>';
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
