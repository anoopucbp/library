// scripts.js

window.addEventListener('scroll', function() {
    const hero = document.querySelector('#hero');
    const scrollPosition = window.scrollY;

    hero.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
});
