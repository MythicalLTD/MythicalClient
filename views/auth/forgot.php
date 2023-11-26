<?php
use MythicalClient\App;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EmailHandler;
use MythicalClient\Handlers\EncryptionHandler;
use MythicalClient\Managers\SessionManager;
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\CloudFlare\Turnstile;

if (isset($_COOKIE['token']) && !$_COOKIE['token'] == null) {
    header('location: /dashboard');
    die();
}
$lang = App::getLang();
$conn = DatabaseConnectionHandler::getConnection();
$session = new SessionManager();

session_start();
$csrf = new MythicalClient\Handlers\CSRFHandler();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['forgot_password'])) {
        if ($csrf->validate('forgot_password-form')) {
            if (ConfigHandler::get("turnstile", "enabled") == "false") {
                $captcha_success = 1;
            } else {
                $captcha_success = Turnstile::validate_captcha($_POST["cf-turnstile-response"], $session->getIP(), ConfigHandler::get("turnstile", "secret_key"));
            }
            if ($captcha_success) {
                if (isset($_POST['email']) && !$_POST['email'] == null) {
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $query = "SELECT * FROM users WHERE email = '" . EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key")) . "'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_assoc($result);
                            $code = base64_encode($session->createKey("password", "reset"));
                            $userdb = $conn->query("SELECT * FROM users WHERE email = '" . EncryptionHandler::encrypt($email,ConfigHandler::get("app","key")) . "'")->fetch_array();
                            if (EmailHandler::SendReset($email, EncryptionHandler::decrypt($row['first_name'], ConfigHandler::get("app", "key")), EncryptionHandler::decrypt($row['last_name'], ConfigHandler::get("app", "key")), $code)) {
                                $conn->query("INSERT INTO `resetpasswords` (`email`, `ownerkey`, `resetkeycode`, `ip_addres`) VALUES ('" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $userdb['token']) . "', '" . mysqli_real_escape_string($conn, $code) . "', '" . mysqli_real_escape_string($conn, $session->getIP()) . "');");
                                $domain = substr(strrchr($email, "@"), 1);
                                $redirections = array('gmail.com' => 'https://mail.google.com', 'yahoo.com' => 'https://mail.yahoo.com', 'hotmail.com' => 'https://outlook.live.com', 'outlook.com' => "https://outlook.live.com", 'gmx.net' => "https://gmx.net", 'icloud.com' => "https://www.icloud.com/mail", 'me.com' => "https://www.icloud.com/mail", 'mac.com' => "https://www.icloud.com/mail", );
                                if (isset($redirections[$domain])) {
                                    header("location: " . $redirections[$domain]);
                                    exit;
                                } else {
                                    header("location: /auth/login");
                                    exit;
                                }
                            } else {
                                header('location: /auth/password/forgot?e=mailserver_down');
                                $conn->close();
                                die();
                            }
                        } else {
                            header('location: /auth/password/forgot?e=not_found_in_db');
                            $conn->close();
                            die();
                        }
                    } else {
                        header('location: /auth/password/forgot?e=not_found_in_db');
                        $conn->close();
                        die();
                    }
                } else {
                    header('location: /auth/password/forgot?e=empty_form');
                    die();
                }
            } else {
                header('location: /auth/password/forgot?e=captcha_failed');
                die();
            }
        } else {
            header('location: /auth/password/forgot?e=csrf_failed');
            die();
        }
    }
}
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
        <?= $lang['forgot_password'] ?>
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
    <script src="/dist/js/demo-theme.js"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <form class="card card-md" method="POST" action="/auth/password/forgot">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">

                        <?= ConfigHandler::get("app", "name") ?>
                    </h2>
                    <p class="text-muted mb-4 text-center">
                        <?= $lang['forgot_password_desc'] ?>
                    </p>

                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['email'] ?>
                        </label>
                        <input type="email" name="email" required class="form-control"
                            placeholder="nayskutzu@mythicalsystems.me">
                    </div>
                    <?= $csrf->input('forgot_password-form'); ?>
                    <div class="form-footer">
                        <?php
                        if (ConfigHandler::get("turnstile", "enabled") == "true" && !ConfigHandler::get("turnstile", "key") == null && !ConfigHandler::get("turnstile", "secret_key") == null) {
                            ?>
                            <center>
                                <div class="cf-turnstile" id="captcha-response"
                                    data-sitekey="<?= ConfigHandler::get("turnstile", "site_key") ?>"></div>
                            </center><br>
                            <?php
                        }
                        ?>
                        <button type="submit" name="forgot_password" class="btn btn-primary w-100">
                            <?= $lang['forgot_password'] ?>
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
    <?php include(__DIR__ . '/../requirements/footer.php') ?>
    <?php include(__DIR__ . '/../components/alerts.php') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () { particlesJS('particles-js', { "particles": { "number": { "value": 160, "density": { "enable": true, "value_area": 800 } }, "color": { "value": "#ffffff" }, "shape": { "type": "circle", "stroke": { "width": 0, "color": "#000000" }, "polygon": { "nb_sides": 5 }, "image": { "src": "img/github.svg", "width": 100, "height": 100 } }, "opacity": { "value": 1, "random": true, "anim": { "enable": true, "speed": 1, "opacity_min": 0, "sync": false } }, "size": { "value": 3, "random": true, "anim": { "enable": false, "speed": 4, "size_min": 0.3, "sync": false } }, "line_linked": { "enable": false, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 }, "move": { "enable": true, "speed": 1, "direction": "none", "random": true, "straight": false, "out_mode": "out", "bounce": false, "attract": { "enable": false, "rotateX": 600, "rotateY": 600 } } }, "interactivity": { "detect_on": "canvas", "events": { "onhover": { "enable": true, "mode": "bubble" }, "onclick": { "enable": true, "mode": "push" }, "resize": true }, "modes": { "grab": { "distance": 0, "line_linked": { "opacity": 1 } }, "bubble": { "distance": 250, "size": 0, "duration": 2, "opacity": 0, "speed": 3 }, "repulse": { "distance": 400, "duration": 0.4 }, "push": { "particles_nb": 4 }, "remove": { "particles_nb": 2 } } }, "retina_detect": true }); });
    </script>
</body>

</html>