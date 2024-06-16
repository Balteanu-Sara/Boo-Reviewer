<?php
session_start();

// Verifică dacă utilizatorul este autentificat
if (isset($_SESSION['user_id'])) {
    // Elimină toate variabilele de sesiune
    $_SESSION = array();

    // Distrugem sesiunea
    session_destroy();

    // Returnăm un răspuns JSON pentru a indica succesul delogării
    $response = array(
        'success' => true,
        'message' => 'Logout successful'
    );

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Dacă utilizatorul nu este autentificat, întoarce o eroare sau un mesaj corespunzător
    $response = array(
        'success' => false,
        'message' => 'User not authenticated'
    );

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
