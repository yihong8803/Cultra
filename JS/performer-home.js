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

    // the doms for the past events
    past_event_bg: document.querySelector(".past-event"),
    past_event_title: document.querySelector(".past-event .content .event-title"),
    past_event_des: document.querySelector(".past-event .content .event-details"),
    past_event_logo: document.querySelector(".past-event .event-image-container .event-logo"),
    past_arrow_left: document.getElementById("past-arrow-left"),
    past_arrow_right: document.getElementById("past-arrow-right"),
};

// dummy data for event picture
const upcoming_images = [
    "./assets/images/PICTURE_OF_EVENT/rsf/rsf_pic.jpg",
    "./assets/images/PICTURE_OF_EVENT/fesco/fesco_pic.jpg",
    "./assets/images/PICTURE_OF_EVENT/pmf/pmf_pic.jpg"
];

// dummy data for event name
const upcoming_names = [
    "Red Sonata Festival 2024", 
    "FESCO 2024", 
    "PETROBOTS Maker Fair 2024"
];

// dummy data for event description
const upcoming_descriptions = [
    "The Red Sonata Fiesta International Arts Festival is a significant cultural event celebrating Chinese heritage. It features various competitions in orchestral music and dance, engaging participants from primary schools, secondary schools, and the public. This year event theme, “Strelitzia and Crane” , symbolizes freedom.",
    "Festival of Colours of the World is an annual event that celebrates the abundance of culture and heritage around the world. Universiti Teknologi PETRONAS.",
    "PETROBOTS Maker Fair 2024 is an event under UTP PETROBOTS. This event is an initiative aimed at introducing robotics skills and providing a platform especially for UTP students to engage with different companies that specialize in engineering fields such as artificial intelligence, robotics, and drones through exhibitions.",
];

// dummy path for event logo
const upcoming_logos = [
    "./assets/images/PICTURE_OF_EVENT/rsf/rsf_logo.png",
    "./assets/images/PICTURE_OF_EVENT/fesco/fesco_logo.png",
    "./assets/images/PICTURE_OF_EVENT/pmf/maker_fair_logo.png"
];

// dummy data for event picture
const past_images = [
    "../assets/images/PAST_EVENT/convofest/Convofest_pic.jpg",
    "./assets/images/PAST_EVENT/grandeur/Grandeur_pic.jpg",
    "./assets/images/PAST_EVENT/roar/Roar2024_pic.png"
];

// dummy data for event name
const past_names = [
    "Convofest 2024", 
    "The Grandeur", 
    "ROAR 2024"
];

// dumy data for event description
const past_descriptions = [
    "CONVOFEST is an annual event in conjuction with UTP's Convocation ceremony, held to celebrate our graduates together with the surrounding community!",
    "The Grandeur is a grand convergence of three arts and culture clubs in UTP, UTPCO, UTPSE and Sanggar Kirana. A concert brimming with culture and diversity, with performances from all three 3 clubs as well as collaboration performances.",
    "The vision for ROAR is to serve as a platform for preparing a well-rounded campus community. The main goal is to assist the Residential College in finding the role model for nurturing a community of excellence in academics, personality and achievement.",
];

// dummy data for past event logo
const past_logos = [
    "./assets/images/PAST_EVENT/convofest/Convofest_logo.png",
    "./assets/images/PAST_EVENT/grandeur/The_Grandeur_logo.png",
    "./assets/images/PAST_EVENT/roar/Roar2024_logo.png"
];

let countUpcomingEvents = upcoming_names.length;
let currUpcoming = 0;

let countPastEvents = past_names.length;
let currPast = 0;


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

function updateUpcomingEvent(index) {
    doms.upcoming_event_bg.style.background = `url(${upcoming_images[index]})`;
    doms.upcoming_event_logo.src = upcoming_logos[index];
    doms.upcoming_event_title.textContent = upcoming_names[index];
    doms.upcoming_event_des.textContent = upcoming_descriptions[index];
}

function updatePastEvent(index) {
    doms.past_event_bg.style.background = `url(${past_images[index]})`;
    doms.past_event_logo.src = past_logos[index];
    doms.past_event_title.textContent = past_names[index];
    doms.past_event_des.textContent = past_descriptions[index];
}

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

// dynamic genearate events for home page
function generateHomeEvent (){
    upcoming_images.forEach((image, index) => {
        const eventCard = document.createElement("div");
        eventCard.className = "card";
        eventCard.classList.add("swiper-slide");
        const eventTitleContainer  = document.createElement("div");
        eventTitleContainer.className = "card-content";

        const eventTitle = document.createElement("span");
        eventTitle.className = "card-title";
        eventTitle.textContent = upcoming_names[index];

        const eventButton = document.createElement("button");
        eventButton.className = "card-btn";
        eventButton.textContent = "View Details";

        eventCard.style.background = `url(${image})`;

        // add the child element to the eventTitleContainer
        eventTitleContainer.appendChild(eventTitle);
        eventTitleContainer.appendChild(eventButton);

        // add the child element to the eventCard
        eventCard.appendChild(eventTitleContainer);
        doms.swiperWrapper.appendChild(eventCard);
    });
};

function init(){
    // Set the default title, description, logo, and background when the program starts
    updateUpcomingEvent(0);
    updatePastEvent(0);
}
init();
generateHomeEvent ();
