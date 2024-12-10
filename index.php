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
                <a>
                    <img src="./assets/images/CULTRA_logo.png" id="logo" alt="Cultra Logo" >
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
    <script>
        const doms = {
    // doms for carousel slider
    carouselList: document.querySelector(".carousel-list"),
    arrowLeft: document.querySelector(".carousel-arrow-left"),
    arrowRight: document.querySelector(".carousel-arrow-right"),
    indicators: document.querySelectorAll(".indicator span"),

    // doms for header
    header: document.querySelector("header"),
    hamburgerBtn: document.querySelector("#hamburger-button"),
    closeMenuBtn: document.querySelector("#close-menu-button"),

    // doms for content
    contentTitle: document.querySelector(".content h1"),
    subContent: document.querySelector(".content p"),
};

// array to store the dedscription for each type of performer
const performerTypes = ["Dancer", "Vocal", "Instrument",];

doms.hamburgerBtn.addEventListener ("click", ()=>{
    doms.header.classList.toggle ("show-mobile-menu");
});

doms.closeMenuBtn.addEventListener ("click", ()=>{
    doms.hamburgerBtn.click();
});

// functions for infinity slider
let currentIndex = 0;
const count = doms.indicators.length; // get the number of images

// function to move the slider to the next/previous image by changing the translateX
function moveTo(index) {
    doms.carouselList.style.transform = `translateX(-${index * 100}vw)`;
    doms.carouselList.style.transition = '0.5s';

    // remove the active class
    var active = document.querySelector(".indicator span.active");
    active.classList.remove('active');

    // add the active class to the current indicator
    doms.indicators[index].classList.add('active');

    // change the content
    doms.contentTitle.innerHTML = performerTypes[index];
    doms.subContent.innerHTML = `${performerTypes[index]} performer. Lorem ipsum dolor sit amet consectetur adipisicing elit. At tempore eius quidem qui quisquam veritatis ullam. Recusandae quia quaerat aliquam explicabo ea nemo? Eveniet facilis vero animi aspernatur excepturi quibusdam!`;

    console.log(currentIndex);

    currentIndex = index; // update the value of the index
}

/*  initialize function 
    add the first image's clone to the position after the last image
    add the last image's clone to the front position of the first image
    
*/
function init(){
    const firstCloned = doms.carouselList.firstElementChild.cloneNode(true);
    const lastCloned = doms.carouselList.lastElementChild.cloneNode(true);

    // change the position of the last clone
    lastCloned.style.marginLeft = '-100';

    doms.carouselList.appendChild(firstCloned);
    doms.carouselList.insertBefore(
        lastCloned,
        doms.carouselList.firstElementChild
    );
}

// move slider using the indicator click event
doms.indicators.forEach((item, i) => {
    item.onclick = function () {
        moveTo(i);
    };
});

init();

function moveLeft(){
    // check whether it is the first image
    if (currentIndex === 0){
        doms.carouselList.style.transform = `translateX(-${count * 100}vw)`; // move the slider window to the back (lastCloned)
        doms.carouselList.style.transition = 'none'; // temporary remove the transition effect
        doms.carouselList.clientHeight; // forced render to avoid JS loading
        currentIndex = count -1;
        moveTo(count - 1); // move to the last image (2)
        
    }else{
        currentIndex--;
        moveTo(currentIndex); // move to the previous image
  
    }
}

function moveRight(){
    // check whether it is the last image
    if (currentIndex === count - 1){
        doms.carouselList.style.transform = `translateX(100)`; // move the slider window to the back (lastCloned)
        doms.carouselList.style.transition = 'none'; // temporary remove the transition effect
        doms.carouselList.clientHeight; // forced render to avoid JS loading
        currentIndex = 0;
        moveTo(currentIndex); // move to the last image (2)
    }else{
        currentIndex++;
        moveTo(currentIndex); // move to the previous image
    }
}

doms.arrowLeft.onclick = moveLeft;
doms.arrowRight.onclick = moveRight;
    </script>
</body>
</html>