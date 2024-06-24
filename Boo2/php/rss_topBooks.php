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

    // preluam cartile cele mai recenzate care fac parte din aceleasi categorii de gen cu cele ale utilizatroului
    $stmt = $conn->prepare('
    SELECT b.book_id, b.title, b.author, r.created_at, COUNT(r.review_id) AS num_reviews, genres.name AS genre
    FROM reviews r
    INNER JOIN books b ON r.book_id = b.book_id
    LEFT JOIN genres ON b.genre_id = genres.genre_id
    WHERE r.user_id != :currentUserId
    AND b.genre_id IN (
        SELECT genre_id
        FROM user_genres
        WHERE user_id = :currentUserId
    )
    GROUP BY b.book_id, b.title, b.author, r.created_at, genres.name
    ORDER BY genres.name, num_reviews DESC
');
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) === 0) {
        echo '<item>';
        echo '<title>Nu există cărți recenzate momentan</title>';
        echo '<description>Nu există cărți recenzate de afișat în acest moment.</description>';
        echo '<pubDate>' . date('D, d M Y H:i:s') . '</pubDate>';
        echo '</item>';
    } else {
        $currentGenre = null;
        foreach ($books as $book) {
            $genre = $book['genre'];
            if ($genre !== $currentGenre) {
                if ($currentGenre !== null) {
                    echo '</item>';
                }
                echo '<item>';
                echo "<title>Cea mai apreciata carte din genul $genre      </title>";
                $currentGenre = $genre;
            }

            $bookId = $book['book_id'];
            $title = htmlspecialchars($book['title']);
            $author = htmlspecialchars($book['author']);
            $numReviews = (int) $book['num_reviews'];
            $link = "http://localhost/Boo2/bookSummary.html?book_id=$bookId";
            $description = htmlspecialchars("Cartea \"$title\" scrisa de $author în genul $genre a primit un numar de $numReviews recenzii.");
            $pubDate = date('D, d M Y H:i:s', strtotime($book['created_at']));

            echo "<title>$title</title>";
            echo "<link>$link</link>";
            echo "<description>$description</description>";
            echo "<pubDate>$pubDate</pubDate>";
        }
        echo '</item>';
    }

    echo '</channel>';
    echo '</rss>';
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
