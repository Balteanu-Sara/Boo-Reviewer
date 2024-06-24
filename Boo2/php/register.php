<?php

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    error_log(print_r($data, true));

    // verificam daca datele sunt corecte si complete
    if (!isset($data->username) || !isset($data->email) || !isset($data->password) || !isset($data->ver_password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Completeaza toate campurile!"));
        exit();
    }

    $username = htmlspecialchars(strip_tags($data->username));
    $email = htmlspecialchars(strip_tags($data->email));
    $password = htmlspecialchars(strip_tags($data->password));
    $ver_password = htmlspecialchars(strip_tags($data->ver_password));

    // verificam daca parolele coincid
    if ($password !== $ver_password) {
        http_response_code(400);
        echo json_encode(array("message" => "Parolele nu coincid!"));
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $db = new DbConnect();
    $conn = $db->getConnection();

    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        $user_id = $conn->lastInsertId();
        http_response_code(201);
        echo json_encode(array("user_id" => $user_id, "message" => "Inregistrare cu succes!"));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Inregistrarea nu s-a putut realiza!"));
    }
}
