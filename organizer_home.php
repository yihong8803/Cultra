<?php

    session_start();
    include ("database.php");

    if(isset($_SESSION['oID'])) {
        $oID = $_SESSION['oID'];
    }

    $sql = "SELECT * FROM ORGANIZER
    WHERE oID = $oID";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  
    // Access individual columns directly
    $oName = $row['oName'];
    $oProfile = $row['oProfile'];
    $oDesc = $row['oDesc'];
    //  echo '<script type="text/javascript">alert("' . $eVenue . '")</script>';
    }

    // Assuming $eId is already defined or sanitized
    $sql = "SELECT e.*, o.*
            FROM ORGANIZER o
            LEFT JOIN event e ON o.oID = e.eOrganizer
            WHERE o.oID = $oID AND e.eDate < CURDATE()";

    $resultPast = mysqli_query($conn, $sql);
    if (mysqli_num_rows($resultPast) > 0) {
        // Initialize variables to store event details and performers
        $pastEvents = [];
        $pastEventCount = 0;

        while ($row = mysqli_fetch_assoc($resultPast)) {
            // Store performer details
            $pastEvents[] = [
                'eID' => $row['eID'],
                'eTitle' => $row['eTitle'],
                'eDesc' => $row['eDesc'],
                'eBanner' => $row['eBanner'],
                'eProfile' => $row['eProfile'],
            ];

        }
        
    } 

    // Assuming $eId is already defined or sanitized
    $sql = "SELECT e.*, o.*
            FROM ORGANIZER o
            LEFT JOIN event e ON o.oID = e.eOrganizer
            WHERE o.oID = $oID AND e.eDate > CURDATE()";

    $resultUpcoming = mysqli_query($conn, $sql);

$sql = "SELECT e.*, o.*
        FROM ORGANIZER o
        LEFT JOIN event e ON o.oID = e.eOrganizer
        WHERE o.oID = $oID AND e.eDate > CURDATE()";

$resultUpcoming = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultUpcoming) > 0) {
        // Initialize variables to store event details and performers
        $upcomingEvents = [];
        $upcomingEventCount = 0;

        while ($row = mysqli_fetch_assoc($resultUpcoming)) {
            // Store performer details
            $upcomingEvents[] = [
                'eID' => $row['eID'],
                'eTitle' => $row['eTitle'],
                'eDesc' => $row['eDesc'],
                'eBanner' => $row['eBanner'],
                'eProfile' => $row['eProfile'],
            ];

        }
    }else{
        $upcomingEvents[] = [
            'eID' => '',
            'eTitle' => 'No Upcoming Events',
            'eDesc' => 'There are no upcoming events',
            'eBanner' => '',
            'eProfile' => '',
        ];
    }




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Links for google font icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/1aec3f9d30.js" crossorigin="anonymous"></script>

    <!-- Link for swipper api's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="organizer_home.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <div class="logo-section">
                    <a href="#">
                        <img src="assets/CULTRA logo.png" alt="Cultra Logo">
                    </a>
                    <h1>CULTRA</h1>
                </div>
                <div class="user-profile">
                <form action="" method="post" name="imgUpload" id="imgUpload" enctype="multipart/form-data">
                <input type="file" id="my_profile" class="uploadImg" name="my_profile" accept="image/jpeg, image/png" style="display: none;" onchange="handleProfileImageSelect(event)">
                    <label for="my_profile">
                        <img src="images/organizer/profile/<?= htmlspecialchars($oProfile) ?>" alt="Profile Photo" class="profile-logo">
                    </label>
                <input type="submit" id="profile_submit_button" name="profile_submit_button" style="display: none;">
            </form>

                    <h3><?= htmlspecialchars($oName) ?></h3>
                </div>
            </div>
            <ul class="menu-links">
                <span id="close-menu-button" class="material-symbols-outlined">
                    close
                </span>
                <li><a href="#">Home</a></li>
                <li><a href="#upcoming-event">Upcoming Event</a></li>
                <li><a href="#past-event">Past Event</a></li>
                <li><a href="h_status.php">Status</a></li>
                <li ><a href="index.php" id="logout-button">Logout</a></li>
            </ul>
            <span id="hamburger-button" class="material-symbols-outlined">
                menu
            </span>
        </nav>
    </header>

    <section id="home" class="search-home">
        <form action="submit" class="search-bar">
            <input type="text" placeholder="Search here...">
            <button type="submit" id="search-button">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
        </form>
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <!-- <button type="submit" class="swiper-slide" value="">Slide1</button>
                <button type="submit" class="swiper-slide" value="">Slide2</button> -->
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
	        <div class="swiper-button-next"></div>
        </div>
    </section>



    <section id="propose-event" class="organizer-home">
        <div class="home-content">
            <h1 class="title">The Bridge Between Talent and Events</h1>
            <ul class="content-list">
                <li>Easy-to-use interface for both performers and organizers.</li>
                <li>Search for finding the perfect match</li>
                <li>Foster successful and memorable events</li>
            </ul>
        </div>
        <a href="propose_event.php" class="propose-button">Propose an Event</a>
    </section>

    
    <h1 class="section-title">UPCOMING EVENT</h1>
    <section id="upcoming-event" class="upcoming-event">
        <div class="content">
            <div class="event-title"></div>
            <div class="event-details"></div>
            <form action="" method="post">
            <button type="submit" id="view-button-upcoming"  name="view-button-upcoming" class="view-event-details" value="">View Details</button>
            </form>
        </div>
        <div class="event-image-container">
            <img class = "event-logo" src="" alt="">
            <div class="switch-logo-button">
                <span id = "upcoming-arrow-left" class="material-symbols-outlined">chevron_left</span>
                <span id = "upcoming-arrow-right" class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>
    </section>

    <h1 class="section-title">PAST EVENT</h1>
    <section id="past-event" class="past-event">
        <div class="content">
            <div class="event-title"></div>
            <div class="event-details"></div>
            <form action="" method="post">
            <button type="submit" id="view-button-past" name="view-button-past" class="view-event-details" value="">View Details</button>
        </form>
        </div>
        <div class="event-image-container">
            <img class = "event-logo" src="" alt="">
            <div class="switch-logo-button">
                <span id = "past-arrow-left" class="material-symbols-outlined">chevron_left</span>
                <span id = "past-arrow-right" class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        

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
            past_button_value: document.getElementById("view-button-past")
        };

        // swiperJS api
        var swiper = new Swiper(".mySwiper", {
                slidesPerView: 4,
                grid: {
                    rows: 2,
                },
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });




        function handleProfileImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profile-banner');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
                document.getElementById('profile_submit_button').click();
            }
        }
        
        var upcomingEvents = <?php echo json_encode($upcomingEvents); ?>;
        var pastEvents = <?php echo json_encode($pastEvents); ?>;

    const imageBasePath = "images/event/banner/";
    const logoBasePath = "images/event/profile/";

    const events = {
        upcoming: upcomingEvents.map(event => ({
            id: event.eID,
            name: event.eTitle,
            description: event.eDesc,
            image: imageBasePath + event.eBanner,
            logo: logoBasePath + event.eProfile
        })),
        past: pastEvents.map(event => ({
            id: event.eID,
            name: event.eTitle,
            description: event.eDesc,
            image: imageBasePath + event.eBanner,
            logo: logoBasePath + event.eProfile
        }))
    };

    console.log("Thehakshdas" + events);

    let countUpcomingEvents = events.upcoming.length;
    let currUpcoming = 0;

    let countPastEvents = events.past.length;
    let currPast = 0;

    // function to genearte the initial performer grid during the first running of webpage
    function generatePerformerGrid(eventsArray, domContainer){
        // <div class="swiper-wrapper">
        //     <button type="submit" class="swiper-slide" value="">Slide1</button>
            eventsArray.forEach(event => {
                const performer_slide= document.createElement("button");
                performer_slide.className = "swiper-slide";
                performer_slide.type = "submit";
                performer_slide.value = "";
                performer_slide.style.background = `url(${event.image})`;
                performer_slide.textContent = event.name;
                
                domContainer.appendChild(performer_slide);
            });     
    };

    // Function to update upcoming event based on index
    function updateUpcomingEvent(index) {
        const event = events.upcoming[index];
        doms.upcoming_event_bg.style.background = `url(${event.image})`;
        doms.upcoming_event_logo.src = event.logo;
        doms.upcoming_event_title.textContent = event.name;
        doms.upcoming_event_des.textContent = event.description;
        doms.upcoming_button_value.value = event.id;
    }

    // Function to update past event based on index
    function updatePastEvent(index) {
        const event = events.past[index];
        doms.past_event_bg.style.background = `url(${event.image})`;
        doms.past_event_logo.src = event.logo;
        doms.past_event_title.textContent = event.name;
        doms.past_event_des.textContent = event.description;
        doms.past_button_value.value = event.id;
    }

    // Initialize event listeners for arrow buttons
    doms.upcoming_arrow_left.addEventListener("click", function() {
        moveUpcomingToLeft();
    });

    doms.upcoming_arrow_right.addEventListener("click", function() {
        moveUpcomingToRight();
    });

    doms.past_arrow_left.addEventListener("click", function() {
        movePastToLeft();
    });

    doms.past_arrow_right.addEventListener("click", function() {
        movePastToRight();
    });


    function moveUpcomingToLeft() {
        currUpcoming = (currUpcoming === 0) ? countUpcomingEvents - 1 : currUpcoming - 1;
        updateUpcomingEvent(currUpcoming);
    }

    function moveUpcomingToRight() {
        currUpcoming = (currUpcoming === countUpcomingEvents - 1) ? 0 : currUpcoming + 1;
        updateUpcomingEvent(currUpcoming);
    }

    function movePastToLeft() {
        currPast = (currPast === 0) ? countPastEvents - 1 : currPast - 1;
        updatePastEvent(currPast);
    }

    function movePastToRight() {
        currPast = (currPast === countPastEvents - 1) ? 0 : currPast + 1;
        updatePastEvent(currPast);
    }

    // code for responsive view
    doms.hamburgerBtn.addEventListener("click", ()=>{
        doms.header.classList.toggle("show-mobile-menu");
    });

    doms.closeMenuBtn.addEventListener("click", ()=>{
        doms.hamburgerBtn.click();
    });

    // generateHomeEvent(events.past, doms.pastEventsContainer);
    function init(){
        // Set the default title, description, logo, and background when the program starts
        updateUpcomingEvent(0);
        updatePastEvent(0);
    }
    init();
    generatePerformerGrid(events.upcoming, doms.swiperWrapper);



    </script>

    <?php
if (isset($_POST['profile_submit_button'])) {   
    $imgName = $_FILES['my_profile']['name'];
    $imgtmp_name = $_FILES['my_profile']['tmp_name'];
    $imgerror = $_FILES['my_profile']['error'];
    if($imgerror == 0){
        $img_ex = pathinfo($imgName, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $imgallowed_exs = array('jpg','jpeg','png');
        if(in_array($img_ex_lc, $imgallowed_exs)){
            $new_img_name = uniqid("image-", true). '.' . $img_ex_lc;
            $img_upload_path = 'images/organizer/profile/'. $new_img_name;
            move_uploaded_file($imgtmp_name, $img_upload_path);

            //SQL Query to database
            $imgName= $new_img_name;
            $sql = "UPDATE ORGANIZER 
                SET oProfile = '$imgName'
                WHERE oID = '$oID'";
            mysqli_query($conn,$sql);

            echo "<script>location.href = 'organizer_home.php';</script>";
        }else{
            echo '<script>alert("You can\'t upload files of this type!")</script>';        
        }
    }
}

    
        if (isset($_POST['view-button-upcoming'])) {
            $button_value = $_POST['view-button-upcoming'];
            $_SESSION['eID'] = $button_value;
            if($button_value){

            echo ("<script>window.location.href='eventDetail.php'</script>");   
            }

        }elseif(isset($_POST['view-button-past'])) {
            $button_value = $_POST['view-button-past'];
            $_SESSION['eID'] = $button_value;
            echo ("<script>window.location.href='eventDetail.php'</script>");
        }


    ?>
</body>
</html>