<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extragem datele din request
    $data = json_decode(file_get_contents("php://input"));

    // verificam daca datele sunt complete
    if (!isset($data->email) || !isset($data->password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Completeaza toate campurile!"));
        exit();
    }

    $email = htmlspecialchars(strip_tags($data->email));
    $password = htmlspecialchars(strip_tags($data->password));

    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // verificam daca userul are genreuri selectate in baza de date
        $queryGenres = "SELECT * FROM user_genres WHERE user_id = :user_id";
        $stmtGenres = $conn->prepare($queryGenres);
        $stmtGenres->bindParam(':user_id', $user['user_id']);
        $stmtGenres->execute();
        $userGenres = $stmtGenres->fetchAll(PDO::FETCH_ASSOC);

        if ($userGenres) {
            // daca are, redirectionam catre loggedPage.html
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            http_response_code(200);
            echo json_encode(array("user_id" => $user['user_id'], "message" => "Autentificare cu succes!", "redirect" => "loggedPage.html"));
        } else {
            // daca nu are genuri selectate, inseamna ca e prima oara cand se autentifica
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            http_response_code(200);
            echo json_encode(array("user_id" => $user['user_id'], "message" => "Autentificare cu succes!", "redirect" => "genres.html"));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Email-ul sau parola sunt incorecte."));
    }
}
