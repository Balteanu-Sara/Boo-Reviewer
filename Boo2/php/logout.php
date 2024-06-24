<?php
session_start();

// distrugem toate datele de sesiune
$_SESSION = array();

// stergem cookie-urile asociate sesiunii, in cazul in care exista
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// distrugem sesiunea in sine
session_destroy();

header("Location: ../principalPage.html");
exit();
