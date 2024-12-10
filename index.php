<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Scroll</title>
    <link rel="stylesheet" href="./assets/CSS/style.css">
    <!-- Links to google font icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navigation-left">
                <a href="#">
                    <img src="./assets/images/CULTRA_logo.png" id="logo" alt="Cultra Logo">
                </a>
                <h1>CULTRA</h1>
            </div>
            <ul class="menu-links">
                <span id="close-menu-button" class="material-symbols-outlined">
                    close
                </span>
                <li><a href="logIn_signUp.php">Sign in</a></li>
                <li ><a href="logIn_signUp.php" id="signup-button">Sign up</a></li>
            </ul>
            <span id="hamburger-button" class="material-symbols-outlined">
                menu
            </span>
        </nav>
    </header>
    <div class="content">
        <h1>Dancer</h1>
       
    </div>
    <div class="carousel-container">
        <div class="carousel-list">
            <div class="carousel-container item-3"><img src="./assets/images/index_page/singer1.png" alt=""></div>
            <div class="carousel-container item-2"><img src="./assets/images/index_page/orchestra1.jpg" alt=""></div>
            <div class="carousel-container item-1"><img src="./assets/images/index_page/Makyong1.jpg" alt=""></div>
        </div>
        <div class="carousel-arrow-container">
            <div class="carousel-arrow carousel-arrow-left">
                <span class="material-symbols-outlined">
                    arrow_back_ios
                </span>
            </div>
            <div class="carousel-arrow carousel-arrow-right">
                <span class="material-symbols-outlined">
                    arrow_forward_ios
                </span>
            </div>
        </div>
        <div class="indicator">
            <span class="active"></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <script src="./JS/script.js"></script>
</body>
</html>