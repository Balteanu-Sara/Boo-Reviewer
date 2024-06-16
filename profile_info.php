<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>My Profile</title>
  </head>
<body>
<?php
session_start();
include("config.php"); // Fișierul de configurare al bazei de date

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    // Dacă nu este autentificat, poți redirecționa către pagina de autentificare sau gestiona altfel situația
    header("Location: ../sign-in.php");
    exit;
}

// Obținem id-ul utilizatorului autentificat
$user_id = $_SESSION['user_id'];

// Interogare pentru a obține informațiile utilizatorului din baza de date
$query = "SELECT username, email FROM users WHERE user_id = $user_id";
$result = mysqli_query($con, $query);

if ($result) {
    // Verificăm dacă avem o înregistrare în rezultat
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        echo "Username: " . $row['username'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";

        $username = $row['username'];
        $email = $row['email'];

        // Salvăm variabilele în sesiune pentru a le putea folosi în alte pagini
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
    } else {
        // Situație de eroare - utilizatorul nu a fost găsit în baza de date
        // Poți trata această situație cum consideri necesar
        echo "Error: User data not found.";
    }
} else {
    // Eroare la interogare
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con); // Închidem conexiunea la baza de date după ce terminăm
?>

</body>
</html>