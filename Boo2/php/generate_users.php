<?php
session_start();
require_once 'db_connect.php';

$db = new DbConnect();
$conn = $db->getConnection();

$usernames = ['ion', 'maria', 'andrei', 'elena', 'mihai', 'ana', 'diana', 'alexandra', 'cristian', 'laura'];
$base_email = 'user';
$password = 'password'; // parola comuna pt toti userii

foreach ($usernames as $username) {
    $email = strtolower($base_email . '_' . $username . '@example.com');
    $hashed_password = password_hash($password . $username, PASSWORD_DEFAULT); //parola: password + username (legat, gen: passwordelena)

    try {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Inserare esuata pentru utilizatorul $username: " . $e->getMessage();
    }
}

echo "Inserare finalizata cu succes";
