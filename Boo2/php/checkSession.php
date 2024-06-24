<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    $isLoggedIn = false;
    $user_id = null;
} else {
    $isLoggedIn = true;
    $user_id = $_SESSION['user_id'];
}

$response = array(
    'isLoggedIn' => $isLoggedIn,
    'user_id' => $user_id,
);

echo json_encode($response);
