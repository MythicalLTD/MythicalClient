<?php
use MythicalClient\App;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\ConfigHandler;

if (isset($_COOKIE['token']) && !$_COOKIE['token'] == null) {
    header('location: /dashboard');
    die();
}
$lang = App::getLang();
$conn = DatabaseConnectionHandler::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['code']) && !$_GET['code'] == "") {
        $code = mysqli_real_escape_string($conn, $_GET['code']);
        $query = "SELECT * FROM resetpasswords WHERE `resetkeycode` = '$code'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            if (isset($_GET['password'])) {
                $ucode = $conn->query("SELECT * FROM resetpasswords WHERE `resetkeycode` = '" . $code . "'")->fetch_array();
                $upassword = mysqli_real_escape_string($conn, $_GET['password']);
                $password = password_hash($upassword, PASSWORD_BCRYPT);
                $conn->query("UPDATE `users` SET `password` = '" . mysqli_real_escape_string($conn, $password) . "' WHERE `users`.`token` = '" . mysqli_real_escape_string($conn, $ucode['ownerkey']) . "';");
                $conn->query("DELETE FROM resetpasswords WHERE `resetpasswords`.`id` = " . mysqli_real_escape_string($conn, $ucode['id']) . "");
                $user_info = $conn->query("SELECT * FROM users WHERE token = '" . mysqli_real_escape_string($conn, $ucode['ownerkey']) . "'")->fetch_array();
                header('location: /auth/login');
                $conn->close();
                die();
            } else {
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
                    <meta charset="utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
                    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
                    <link rel="shortcut icon" type="image/x-icon" href="<?= ConfigHandler::get("app", "logo") ?>">
                    <link href="/auth/dist/css/tabler.css" rel="stylesheet" />
                    <link href="/auth/dist/css/tabler-flags.css" rel="stylesheet" />
                    <link href="/auth/dist/css/tabler-payments.css" rel="stylesheet" />
                    <link href="/auth/dist/css/tabler-vendors.css" rel="stylesheet" />
                    <link href="/auth/dist/css/demo.css" rel="stylesheet" />
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
                    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
                    <style>
                        @import url('https://rsms.me/inter/inter.css');

                        :root {
                            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
                        }

                        body {
                            font-feature-settings: "cv03", "cv04", "cv11";
                        }
                    </style>
                    <link href="/auth/dist/css/custom.css" rel="stylesheet" />
                    <link href="/auth/dist/css/preloader.css" rel="stylesheet" />
                    <title>
                        <?= ConfigHandler::get("app", "name") ?> -
                        <?= $lang['login'] ?>
                    </title>
                    <style>
                        body {
                            overflow: auto;
                            background-image: url('<?= ConfigHandler::get("app", "background") ?>');
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: center center;
                        }
                    </style>
                </head>

                <body class="d-flex flex-column">
                    <div id="preloader">
                        <div id="loader"></div>
                    </div>
                    <div id="particles-js"></div>
                    <script src="/auth/dist/js/demo-theme.js"></script>
                    <div class="page page-center">
                        <div class="container container-tight py-4">
                            <form class="card card-md" method="GET" action="/auth/password/reset">
                                <div class="card-body">
                                    <h2 class="card-title text-center mb-4">
                                        <?= ConfigHandler::get("app", "name") ?>
                                    </h2>
                                    <p class="text-muted mb-4 text-center">
                                        <?= $lang['reset_password_desc'] ?>
                                    </p>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <?= $lang['password'] ?>
                                        </label>
                                        <input type="password" name="password" required class="form-control">
                                    </div>
                                    <div class="form-footer">
                                        <input type="hidden" name="code" value="<?= $_GET['code'] ?>">
                                        <button type="submit" name="reset_password" class="btn btn-primary w-100">
                                            <?= $lang['reset_password'] ?>
                                        </button>
                                    </div>
                                    <div class="text-center text-muted mt-3">
                                        <?= $lang['forgot_go_back'] ?> <a href="/auth/login" tabindex="-1">
                                            <?= $lang['login'] ?>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script src="/auth/dist/libs/apexcharts/auth/dist/apexcharts.min.js" defer></script>
                    <script src="/auth/dist/libs/jsvectormap/auth/dist/js/jsvectormap.min.js" defer></script>
                    <script src="/auth/dist/libs/jsvectormap/auth/dist/maps/world.js" defer></script>
                    <script src="/auth/dist/libs/jsvectormap/auth/dist/maps/world-merc.js" defer></script>
                    <script src="/auth/dist/js/tabler.js" defer></script>
                    <script src="/auth/dist/js/demo.js" defer></script>
                    <script src="/auth/dist/js/preloader.js" defer></script>
                    <?php include(__DIR__ . '/../components/alerts.php') ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () { particlesJS('particles-js', { "particles": { "number": { "value": 160, "density": { "enable": true, "value_area": 800 } }, "color": { "value": "#ffffff" }, "shape": { "type": "circle", "stroke": { "width": 0, "color": "#000000" }, "polygon": { "nb_sides": 5 }, "image": { "src": "img/github.svg", "width": 100, "height": 100 } }, "opacity": { "value": 1, "random": true, "anim": { "enable": true, "speed": 1, "opacity_min": 0, "sync": false } }, "size": { "value": 3, "random": true, "anim": { "enable": false, "speed": 4, "size_min": 0.3, "sync": false } }, "line_linked": { "enable": false, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 }, "move": { "enable": true, "speed": 1, "direction": "none", "random": true, "straight": false, "out_mode": "out", "bounce": false, "attract": { "enable": false, "rotateX": 600, "rotateY": 600 } } }, "interactivity": { "detect_on": "canvas", "events": { "onhover": { "enable": true, "mode": "bubble" }, "onclick": { "enable": true, "mode": "push" }, "resize": true }, "modes": { "grab": { "distance": 0, "line_linked": { "opacity": 1 } }, "bubble": { "distance": 250, "size": 0, "duration": 2, "opacity": 0, "speed": 3 }, "repulse": { "distance": 400, "duration": 0.4 }, "push": { "particles_nb": 4 }, "remove": { "particles_nb": 2 } } }, "retina_detect": true }); });
                    </script>
                </body>

                </html>
                <?php
            }

        } else {
            header('location: /auth/login?e=not_found_in_db');
            die();
        }
    } else {
        header('location: /auth/login?e=not_found_in_db');
        die();
    }
}
?>