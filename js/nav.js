/**
 * RESPOSIVE NAVBAR TOGGLES
 */

const navSlide = () => {
    const burger = document.querySelector('.burger');
    const nav = document. querySelector('.nav-links');

    burger.addEventListener('click', () => {

        //toggle nav
        nav.classList.toggle('nav-active');

        //burger animation
        burger.classList.toggle('toggle');
    });
}

navSlide();