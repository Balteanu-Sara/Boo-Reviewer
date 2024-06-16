<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boo Make an Account</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<body>
<?php
session_start();
include("config.php");

$error_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verPassword = $_POST['ver-password'];

    // Verificarea dacă emailul este unic
    $verify_email_query = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
    if (mysqli_num_rows($verify_email_query) != 0) {
        $error_message = "This email is already in use. Please try another one.";
    } else {
        // Verificarea dacă username-ul este unic
        $verify_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        if (mysqli_num_rows($verify_username_query) != 0) {
            $error_message = "This username is already taken. Please choose another one.";
        } else if ($password !== $verPassword) {
            $error_message = "Passwords do not match.";
        } else {
            // Criptarea parolei
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Salvarea în baza de date
            $query = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$hashed_password')";
            mysqli_query($con, $query) or die("Error Occured");

            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $_SESSION['success_message'] = "Registration successful!";
            header("Location: ../genres.php");
            exit;
        }
    }
}
?>

</body>
</html>