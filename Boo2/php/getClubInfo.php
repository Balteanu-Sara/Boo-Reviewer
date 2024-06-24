<?php
require_once 'db_connect.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => 'User not authenticated.'));
    exit;
}

$user_id = $_SESSION['user_id'];


try {
    $db = new DbConnect();
    $conn = $db->getConnection();

    switch ($method) {
        case 'GET':
            if (isset($_GET['club_id'])) {
                $club_id = intval($_GET['club_id']);

                $stmt = $conn->prepare('SELECT * FROM book_clubs WHERE group_id = ?');
                $stmt->execute([$club_id]);
                $club = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$club) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Club not found']);
                    exit;
                }

                $stmt = $conn->prepare('SELECT name as club_name FROM book_clubs WHERE group_id = ?'); // preluam numele clubului
                $stmt->execute([$club_id]);
                $club_name = $stmt->fetch(PDO::FETCH_ASSOC)['club_name'];

                $stmt = $conn->prepare('SELECT COUNT(*) as member_count FROM user_groups WHERE group_id = ?'); // numaram sa vedem cati membri fac parte din grup
                $stmt->execute([$club_id]);
                $member_count = $stmt->fetch(PDO::FETCH_ASSOC)['member_count'];

                // numaram reviewurile date de userii care fac parte din grup
                $stmt = $conn->prepare(' 
                    SELECT COUNT(*) as review_count 
                    FROM reviews 
                    WHERE user_id IN (
                        SELECT user_id 
                        FROM user_groups 
                        WHERE group_id = ?
                    )
                ');
                $stmt->execute([$club_id]);
                $review_count = $stmt->fetch(PDO::FETCH_ASSOC)['review_count'];

                // numaram cartile citite de userii care fac parte din grup
                $stmt = $conn->prepare('
                    SELECT COUNT(*) as books_read_count 
                    FROM user_books 
                    WHERE user_id IN (
                        SELECT user_id 
                        FROM user_groups
                        WHERE group_id = ?
                    )
                    AND status = "read"
                ');
                $stmt->execute([$club_id]);
                $books_read_count = $stmt->fetch(PDO::FETCH_ASSOC)['books_read_count'];

                // preluam cartile cele mai citite de utilizatroii care fac parte din grup
                $stmt = $conn->prepare('
                    SELECT b.book_id, b.title, COUNT(*) as read_count
                    FROM user_books ub
                    INNER JOIN books b ON ub.book_id = b.book_id
                    WHERE ub.user_id IN (
                        SELECT user_id 
                        FROM user_groups 
                        WHERE group_id = ?
                    )
                    AND ub.status = "read"
                    GROUP BY ub.book_id
                    ORDER BY read_count DESC
                    LIMIT 3
                ');
                $stmt->execute([$club_id]);
                $top_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // vedem cele mai recente carti din colectiile utilizatorilor
                $stmt = $conn->prepare('
                    SELECT b.book_id, u.username, b.title, ub.status
                    FROM user_books ub
                    INNER JOIN books b ON ub.book_id = b.book_id
                    INNER JOIN users u ON ub.user_id = u.user_id
                    WHERE ub.user_id IN (
                        SELECT user_id 
                        FROM user_groups 
                        WHERE group_id = ?
                    )
                    ORDER BY ub.created_at DESC
                    LIMIT 100
                ');
                $stmt->execute([$club_id]);
                $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);


                // preluam membrii grupului
                $stmt = $conn->prepare('
                SELECT u.username, u.user_id
                FROM users u
                INNER JOIN user_groups ug ON u.user_id = ug.user_id
                WHERE ug.group_id = ?
            ');
                $stmt->execute([$club_id]);
                $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // trimitem raspunsul
                $response = [
                    'currentUserId' => $user_id,
                    'club_name' => $club_name,
                    'member_count' => intval($member_count),
                    'review_count' => intval($review_count),
                    'books_read_count' => intval($books_read_count),
                    'top_books' => $top_books,
                    'activity' => $activity,
                    'members' => $members
                ];

                echo json_encode($response);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Missing club_id parameter']);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
