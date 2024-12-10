<?php
    session_start();
    include ("database.php");

    if(isset($_SESSION['pID'])) {
        $pID = $_SESSION['pID'];
    }

    $sql = "SELECT * FROM PERFORMER
    WHERE pID = $pID";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  
    // Access individual columns directly
    $pName = $row['pName'];
    $pProfile = $row['pProfile'];
    $pDesc = $row['pDesc'];
//  echo '<script type="text/javascript">alert("' . $eVenue . '")</script>';
}


// Assuming $eId is already defined or sanitized
$sql = "SELECT e.*, p.*
        FROM PERFORMER p
        LEFT JOIN eventperformer ep ON p.pID = ep.pID
        LEFT JOIN EVENT e ON ep.eID = e.eID
        WHERE p.pID = $pID
          AND e.eDate < CURDATE()";

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
$sql = "SELECT e.*, p.*
        FROM PERFORMER p
        LEFT JOIN eventperformer ep ON p.pID = ep.pID
        LEFT JOIN EVENT e ON ep.eID = e.eID
        WHERE p.pID = $pID
          AND e.eDate >= CURDATE()";

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
                <li ><a href="index.php" id="logout-button">Logout</a></li>
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
            <img class = "event-logo" src="" alt="">
            <div class="switch-logo-button">
                <span id = "past-arrow-left" class="material-symbols-outlined">chevron_left</span>
                <span id = "past-arrow-right" class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>
    </section>

    <script>
        window.alert("ASD");
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

    console.log(events);
    

let countUpcomingEvents = events.upcoming.length;
let currUpcoming = 0;

let countPastEvents = events.past.length;
let currPast = 0;
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

// Function to generate event cards dynamically
function generateHomeEvent(eventsArray, domContainer) {
    eventsArray.forEach(event => {
        const eventCard = document.createElement("div");
        eventCard.className = "card swiper-slide";
        
        const eventTitleContainer = document.createElement("div");
        eventTitleContainer.className = "card-content";

        const eventTitle = document.createElement("span");
        eventTitle.className = "card-title";
        eventTitle.textContent = event.name;

        const eventButton = document.createElement("button");
        eventButton.className = "card-btn";
        eventButton.textContent = "View Details";

        eventCard.style.background = `url(${event.image})`;

        // Add event listeners or other dynamic behavior to eventButton if needed

        eventTitleContainer.appendChild(eventTitle);
        eventTitleContainer.appendChild(eventButton);

        eventCard.appendChild(eventTitleContainer);
        domContainer.appendChild(eventCard);
    });
}

    // Call the function to generate past events (if needed)
    // generateHomeEvent(events.past, doms.pastEventsContainer);
    function init(){
        // Set the default title, description, logo, and background when the program starts
        updateUpcomingEvent(0);
        updatePastEvent(0);
    }
    init();
    // Call the function to generate upcoming events
    generateHomeEvent(events.upcoming, doms.swiperWrapper);

    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper_home = new Swiper(".Home .mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        initialSlide: 0,
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 300,
            modifier: 1,
            slideShadows: true,
        },
        // autoplay: {
		//     delay: 6000,
        // },
        
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
	    },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
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