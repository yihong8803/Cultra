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
        moveTo(count - 1); // move to the last image (2)
    }else{
        moveTo(currentIndex - 1); // move to the previous image
    }
}

function moveRight(){
    // check whether it is the last image
    if (currentIndex === count - 1){
        doms.carouselList.style.transform = `translateX(100)`; // move the slider window to the back (lastCloned)
        doms.carouselList.style.transition = 'none'; // temporary remove the transition effect
        doms.carouselList.clientHeight; // forced render to avoid JS loading
        moveTo(0); // move to the last image (2)
    }else{
        moveTo(currentIndex + 1); // move to the previous image
    }
}

doms.arrowLeft.onclick = moveLeft;
doms.arrowRight.onclick = moveRight;