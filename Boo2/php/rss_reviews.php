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

    $stmt = $conn->prepare('
    SELECT r.book_id, MAX(r.created_at) AS latest_review, b.title, u.username
    FROM reviews r
    INNER JOIN books b ON r.book_id = b.book_id
    INNER JOIN users u ON r.user_id = u.user_id
    WHERE r.user_id != :currentUserId
    GROUP BY r.book_id, b.title, u.username
    ORDER BY latest_review DESC
    LIMIT 4
    ');
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // daca userii nu au facut nicio recenzie 
    if (count($reviews) === 0) {
        echo '<item>';
        echo '<title>Nu exista recenzii momentan</title>';
        echo '<description>Nu exista recenzii noi de afisat în acest moment.</description>';
        echo '<pubDate>' . date('D, d M Y H:i:s') . '</pubDate>';
        echo '</item>';
    } else {
        foreach ($reviews as $review) {
            $bookId = $review['book_id'];
            $reviewer = htmlspecialchars($review['username']);
            $title = htmlspecialchars($review['title']);
            $link = "http://localhost/Boo2/seeReview.html?bookId=$bookId";
            $description = htmlspecialchars("Userul $reviewer a adaugat o recenzie noua pentru cartea \"$title\".");
            $pubDate = date('D, d M Y H:i:s', strtotime($review['latest_review']));

            echo '<item>';
            echo "<title>Recenzie noua pentru cartea \"$title\"</title>";
            echo "<link>$link</link>";
            echo "<description>$description</description>";
            echo "<pubDate>$pubDate</pubDate>";
            echo '</item>';
        }
    }

    echo '</channel>';
    echo '</rss>';
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
