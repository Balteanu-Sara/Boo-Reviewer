<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <title>Boo Sign In</title>
</head>
<body>
    <header class="header">
        <img class="header__logo" src="styles/images/logouri/logo-color.png" alt="Boo_logo" />
        <h1 class="header__title">Sign In</h1>
    </header>
    <main class="main">
        <form action="php/autentification.php" method="POST" class="account-form">
            <?php if (!empty($error_message)): ?>
              <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <input class="account-form__input" type="email" name="email" id="email" placeholder="Email" />
            <input class="account-form__input" type="password" name="password" id="password" placeholder="Password" />
            <button type="submit" class="account-form__submit-btn btn">Sign In</button>
        </form>
        <h4 class="account-message">
            New to Boo? <br />
            <a href="createAccount.php" class="account-message__link sign-in">Make an Account</a>
        </h4>
    </main>
    <footer class="footer">
        <div class="footer__links">
            <ul class="footer__list">
                <li class="footer__item">
                    <a href="" class="footer__link">Terms of Service</a>
                </li>
                <li class="footer__item">
                    <a href="" class="footer__link">Privacy</a>
                </li>
                <li class="footer__item">
                    <a href="helpLogged.php" class="footer__link">Help</a>
                </li>
            </ul>
        </div>
    </footer>
</body>
</html>