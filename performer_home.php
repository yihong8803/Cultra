<?php
session_start();
include("database.php");

if (isset($_SESSION['pID'])) {
    $pID = $_SESSION['pID'];
}

$sql = "SELECT * FROM PERFORMER WHERE pID = $pID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  
    $pName = $row['pName'];
    $pProfile = $row['pProfile'];
    $pDesc = $row['pDesc'];
}

$sql = "SELECT e.*, p.* FROM PERFORMER p LEFT JOIN eventperformer ep ON p.pID = ep.pID LEFT JOIN EVENT e ON ep.eID = e.eID WHERE p.pID = $pID AND e.eDate < CURDATE()";
$resultPast = mysqli_query($conn, $sql);
$pastEvents = [];
if (mysqli_num_rows($resultPast) > 0) {
    while ($row = mysqli_fetch_assoc($resultPast)) {
        $pastEvents[] = [
            'eID' => $row['eID'],
            'eTitle' => $row['eTitle'],
            'eDesc' => $row['eDesc'],
            'eBanner' => $row['eBanner'],
            'eProfile' => $row['eProfile'],
        ];
    }
}

$sql = "SELECT e.*, p.* FROM PERFORMER p LEFT JOIN eventperformer ep ON p.pID = ep.pID LEFT JOIN EVENT e ON ep.eID = e.eID WHERE p.pID = $pID AND e.eDate >= CURDATE()";
$resultUpcoming = mysqli_query($conn, $sql);
$upcomingEvents = [];
if (mysqli_num_rows($resultUpcoming) > 0) {
    while ($row = mysqli_fetch_assoc($resultUpcoming)) {
        $upcomingEvents[] = [
            'eID' => $row['eID'],
            'eTitle' => $row['eTitle'],
            'eDesc' => $row['eDesc'],
            'eBanner' => $row['eBanner'],
            'eProfile' => $row['eProfile'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultra</title>
    
    <!-- Links for google font icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/1aec3f9d30.js" crossorigin="anonymous"></script>
    <!-- Link for swipper api's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="performer_home.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <div class="logo-section">
                    <a href="#">
                        <img src="./assets/images/CULTRA_logo.png" alt="Cultra Logo">
                    </a>
                    <h1>CULTRA</h1>
                </div>
                <div class="user-profile">
                    <a href="profileDetail.php">
                        <img src="images/performer/profile/<?= htmlspecialchars($pProfile) ?>">
                    </a>
                    <h3><?= htmlspecialchars($pName) ?></h3>
                </div>
            </div>
            <ul class="menu-links">
                <span id="close-menu-button" class="material-symbols-outlined">
                    close
                </span>
                <li><a href="#">Home</a></li>
                <li><a href="#upcoming-event">Upcoming Event</a></li>
                <li><a href="#past-event">Past Event</a></li>
                <li><a href="r_status.php">Status</a></li>
                <li><a href="index.php" id="logout-button">Logout</a></li>
            </ul>
            <span id="hamburger-button" class="material-symbols-outlined">
                menu
            </span>
        </nav>
    </header>

    <section id="home" class="Home">
        <form action="submit" class="search-bar">
            <input type="text" placeholder="Search here...">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
        </form>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>

    <h1 id="upcoming-event" class="section-title">UPCOMING EVENT</h1>
    <section class="upcoming-event">
        <div class="content">
            <div class="event-title"></div>
            <div class="event-details"></div>
            <form action="" method="post">
                <button type="submit" id="view-button-upcoming" name="view-button-upcoming" class="view-event-details" value="">View Details</button>
            </form>
        </div>
        <div class="event-image-container">
            <img class="event-logo" src="" alt="">
            <div class="switch-logo-button">
                <span id="upcoming-arrow-left" class="material-symbols-outlined">chevron_left</span>
                <span id="upcoming-arrow-right" class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>
    </section>

    <h1 id="past-event" class="section-title">PAST EVENT</h1>
    <section class="past-event">
        <div class="content">
            <div class="event-title"></div>
            <div class="event-details"></div>
            <form action="" method="post">
                <button type="submit" id="view-button-past" name="view-button-past" class="view-event-details" value="">View Details</button>
            </form>
        </div>
        <div class="event-image-container">
            <img class="event-logo" src="" alt="">
            <div class="switch-logo-button">
                <span id="past-arrow-left" class="material-symbols-outlined">chevron_left</span>
                <span id="past-arrow-right" class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function toEventDetail(){
            window.location.href = `eventDetail.php`;
        }
        
        const doms = {
            header: document.querySelector("header"),
            hamburgerBtn: document.querySelector("#hamburger-button"),
            closeMenuBtn: document.querySelector("#close-menu-button"),
            swiperWrapper: document.querySelector(".swiper-wrapper"),

            // the doms for the upcoming events
            upcoming_event_bg: document.querySelector(".upcoming-event"),
            upcoming_event_title: document.querySelector(".upcoming-event .content .event-title"),
            upcoming_event_des: document.querySelector(".upcoming-event .content .event-details"),
            upcoming_event_logo: document.querySelector(".upcoming-event .event-image-container .event-logo"),
            upcoming_arrow_left: document.getElementById("upcoming-arrow-left"),
            upcoming_arrow_right: document.getElementById("upcoming-arrow-right"),
            upcoming_button_value: document.getElementById("view-button-upcoming"),

            // the doms for the past events
            past_event_bg: document.querySelector(".past-event"),
            past_event_title: document.querySelector(".past-event .content .event-title"),
            past_event_des: document.querySelector(".past-event .content .event-details"),
            past_event_logo: document.querySelector(".past-event .event-image-container .event-logo"),
            past_arrow_left: document.getElementById("past-arrow-left"),
            past_arrow_right: document.getElementById("past-arrow-right"),
            past_button_value: document.getElementById("view-button-past"),
        };

        var upcomingEvents = <?php echo json_encode($upcomingEvents); ?>;
        var pastEvents = <?php echo json_encode($pastEvents); ?>;

        const imageBasePath = "images/event/banner/";
        const logoBasePath = "images/event/profile/";

        const logoStyle = "event-logo";
        let upcomingIndex = 0;
        let pastIndex = 0;

        const eventFields = ["eTitle", "eDesc"];
        const eventLogo = "eProfile";

        function renderEvent(eventObj, titleDOM, descriptionDOM) {
            titleDOM.innerText = eventObj.eTitle;
            descriptionDOM.innerText = eventObj.eDesc;
        }

        function renderEventLogo(eventObj, logoDOM) {
            logoDOM.src = logoBasePath + eventObj.eProfile;
            logoDOM.classList.add(logoStyle);
        }

        function toggleButtonValue(eventObj, buttonValueDOM){
            buttonValueDOM.value = eventObj.eID;
        }

        doms.upcoming_event_bg.style.backgroundImage = `url(${imageBasePath + upcomingEvents[upcomingIndex].eBanner})`;
        renderEvent(upcomingEvents[upcomingIndex], doms.upcoming_event_title, doms.upcoming_event_des);
        renderEventLogo(upcomingEvents[upcomingIndex], doms.upcoming_event_logo);
        toggleButtonValue(upcomingEvents[upcomingIndex], doms.upcoming_button_value);

        doms.past_event_bg.style.backgroundImage = `url(${imageBasePath + pastEvents[pastIndex].eBanner})`;
        renderEvent(pastEvents[pastIndex], doms.past_event_title, doms.past_event_des);
        renderEventLogo(pastEvents[pastIndex], doms.past_event_logo);
        toggleButtonValue(pastEvents[pastIndex], doms.past_button_value);

        doms.hamburgerBtn.addEventListener("click", () => {
            doms.header.classList.add("menu-open");
        });

        doms.closeMenuBtn.addEventListener("click", () => {
            doms.header.classList.remove("menu-open");
        });

        doms.upcoming_arrow_left.addEventListener("click", () => {
            upcomingIndex = (upcomingIndex - 1 + upcomingEvents.length) % upcomingEvents.length;
            doms.upcoming_event_bg.style.backgroundImage = `url(${imageBasePath + upcomingEvents[upcomingIndex].eBanner})`;
            renderEvent(upcomingEvents[upcomingIndex], doms.upcoming_event_title, doms.upcoming_event_des);
            renderEventLogo(upcomingEvents[upcomingIndex], doms.upcoming_event_logo);
            toggleButtonValue(upcomingEvents[upcomingIndex], doms.upcoming_button_value);
        });

        doms.upcoming_arrow_right.addEventListener("click", () => {
            upcomingIndex = (upcomingIndex + 1) % upcomingEvents.length;
            doms.upcoming_event_bg.style.backgroundImage = `url(${imageBasePath + upcomingEvents[upcomingIndex].eBanner})`;
            renderEvent(upcomingEvents[upcomingIndex], doms.upcoming_event_title, doms.upcoming_event_des);
            renderEventLogo(upcomingEvents[upcomingIndex], doms.upcoming_event_logo);
            toggleButtonValue(upcomingEvents[upcomingIndex], doms.upcoming_button_value);
        });

        doms.past_arrow_left.addEventListener("click", () => {
            pastIndex = (pastIndex - 1 + pastEvents.length) % pastEvents.length;
            doms.past_event_bg.style.backgroundImage = `url(${imageBasePath + pastEvents[pastIndex].eBanner})`;
            renderEvent(pastEvents[pastIndex], doms.past_event_title, doms.past_event_des);
            renderEventLogo(pastEvents[pastIndex], doms.past_event_logo);
            toggleButtonValue(pastEvents[pastIndex], doms.past_button_value);
        });

        doms.past_arrow_right.addEventListener("click", () => {
            pastIndex = (pastIndex + 1) % pastEvents.length;
            doms.past_event_bg.style.backgroundImage = `url(${imageBasePath + pastEvents[pastIndex].eBanner})`;
            renderEvent(pastEvents[pastIndex], doms.past_event_title, doms.past_event_des);
            renderEventLogo(pastEvents[pastIndex], doms.past_event_logo);
            toggleButtonValue(pastEvents[pastIndex], doms.past_button_value);
        });

        doms.upcoming_button_value.addEventListener("click", toEventDetail);
        doms.past_button_value.addEventListener("click", toEventDetail);
    });
    </script>
<?php

if (isset($_POST['view-button-upcoming'])) {
    $button_value = $_POST['view-button-upcoming'];
    $_SESSION['eID'] = $button_value;
    echo ("<script>window.location.href='eventDetail.php'</script>");

}elseif(isset($_POST['view-button-past'])) {
    $button_value = $_POST['view-button-past'];
    $_SESSION['eID'] = $button_value;
    echo ("<script>window.location.href='eventDetail.php'</script>");
}

?>

</body>
</html>
