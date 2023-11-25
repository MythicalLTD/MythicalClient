<?php
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\App;

$lang = App::getLang();

?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <?php include(__DIR__ . '/../requirements/head.php') ?>
    <title>
        <?= ConfigHandler::get("app", "name") ?> -
        <?= $lang["maintenance"]?>
    </title>
</head>

<body class=" border-top-wide border-primary d-flex flex-column">
    <div id="preloader">
        <div id="loader"></div>
    </div>
    <div id="particles-js"></div>
    <script src="/dist/js/demo-theme.js"></script>
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-img"><img src="/static/illustrations/undraw_quitting_time_dm8t.svg" height="128"
                        alt="">
                </div>
                <p class="empty-title"><?= $lang["error_maintenance_title"]?></p>
                <p class="empty-subtitle text-muted">
                    <?= $lang['error_maintenance_subtitle']?>
                </p>
                <div class="empty-action">
                    <a href="/" class="btn btn-primary">
                        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="M5 12l6 6" />
                            <path d="M5 12l6 -6" />
                        </svg>
                        <?= $lang['error_to_home'] ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include(__DIR__ . '/../requirements/footer.php') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () { particlesJS('particles-js', { "particles": { "number": { "value": 160, "density": { "enable": true, "value_area": 800 } }, "color": { "value": "#ffffff" }, "shape": { "type": "circle", "stroke": { "width": 0, "color": "#000000" }, "polygon": { "nb_sides": 5 }, "image": { "src": "img/github.svg", "width": 100, "height": 100 } }, "opacity": { "value": 1, "random": true, "anim": { "enable": true, "speed": 1, "opacity_min": 0, "sync": false } }, "size": { "value": 3, "random": true, "anim": { "enable": false, "speed": 4, "size_min": 0.3, "sync": false } }, "line_linked": { "enable": false, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 }, "move": { "enable": true, "speed": 1, "direction": "none", "random": true, "straight": false, "out_mode": "out", "bounce": false, "attract": { "enable": false, "rotateX": 600, "rotateY": 600 } } }, "interactivity": { "detect_on": "canvas", "events": { "onhover": { "enable": true, "mode": "bubble" }, "onclick": { "enable": true, "mode": "push" }, "resize": true }, "modes": { "grab": { "distance": 0, "line_linked": { "opacity": 1 } }, "bubble": { "distance": 250, "size": 0, "duration": 2, "opacity": 0, "speed": 3 }, "repulse": { "distance": 400, "duration": 0.4 }, "push": { "particles_nb": 4 }, "remove": { "particles_nb": 2 } } }, "retina_detect": true }); });
    </script>
</body>

</html>