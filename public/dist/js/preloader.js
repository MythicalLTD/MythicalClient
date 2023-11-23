setTimeout(() => {
    const preloader = document.getElementById('preloader');
    preloader.style.opacity = '0';
    setTimeout(() => {
        preloader.style.display = 'none';
    }, 2500);
}, 10000);

window.addEventListener('load', function () {
    document.body.classList.add('loaded');
});