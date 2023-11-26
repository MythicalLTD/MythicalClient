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
    if (isset($_POST['login'])) {
        if ($csrf->validate('login-form')) {
            if (ConfigHandler::get("turnstile", "enabled") == "false") {
                $captcha_success = 1;
            } else {
                $captcha_success = Turnstile::validate_captcha($_POST["cf-turnstile-response"], $session->getIP(), ConfigHandler::get("turnstile", "secret_key"));
            }
            if ($captcha_success) {
                if (isset($_POST['email']) && !$_POST['email'] == null && isset($_POST['password']) && !$_POST['password'] == null) {
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $password = mysqli_real_escape_string($conn, $_POST['password']);
                    $query = "SELECT * FROM users WHERE email = '" . EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key")) . "'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_assoc($result);
                            $hashedPassword = $row['password'];
                            if (password_verify($password, $hashedPassword)) {
                                $banned = $row['banned'];
                                if (!$banned == null) {
                                    header('location: /auth/login?e=banned');
                                    $conn->close();
                                    die();
                                }
                                $account_token = $row['token'];
                                $url = "http://ipinfo.io/" . $session->getIP() . "/json";
                                $data = json_decode(file_get_contents($url), true);
                                if (ConfigHandler::get("other", "allow_vpn") == "false") {
                                    if (isset($data['error']) || $data['org'] == "AS1221 Telstra Pty Ltd") {
                                        header('location: /auth/login?e=vpn');
                                        $conn->close();
                                        die();
                                    }
                                }
                                $iploc = $data['country'] . ' ' . $data['city'] . '';
                                $userids = array();
                                $loginlogs = mysqli_query($conn, "SELECT * FROM ip_logs WHERE usertoken = '" . mysqli_real_escape_string($conn, $account_token) . "'");
                                foreach ($loginlogs as $login) {
                                    $ip = $login['ipaddr'];
                                    if ($ip == "12.34.56.78") {
                                        continue;
                                    }
                                    $saio = mysqli_query($conn, "SELECT * FROM ip_logs WHERE ipaddr = '" . mysqli_real_escape_string($conn, $ip) . "'");
                                    foreach ($saio as $hello) {
                                        if (in_array($hello['usertoken'], $userids)) {
                                            continue;
                                        }
                                        if ($hello['usertoken'] == $account_token) {
                                            continue;
                                        }
                                        array_push($userids, $hello['usertoken']);
                                    }
                                }
                                if (ConfigHandler::get("other", "allow_alts") == "false") {
                                    if (count($userids) !== 0) {
                                        header('location: /auth/login?e=alting');
                                        $conn->close();
                                        die();
                                    }
                                }
                                $conn->query("INSERT INTO ip_logs (ipaddr, usertoken) VALUES ('" . mysqli_real_escape_string($conn, $session->getIP()) . "', '" . mysqli_real_escape_string($conn, $account_token) . "')");

                                $cookie_name = 'token';
                                $cookie_value = $account_token;
                                setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60), '/');
                                $conn->query("UPDATE `users` SET `last_ip` = '" . EncryptionHandler::encrypt(mysqli_real_escape_string($conn, $session->getIP()), ConfigHandler::get("app", "key")) . "' WHERE `users`.`token` = '" . mysqli_real_escape_string($conn, $account_token) . "';");
                                EmailHandler::SendLogin($email, EncryptionHandler::decrypt(mysqli_real_escape_string($conn, $row['first_name']), ConfigHandler::get("app", "key")), EncryptionHandler::decrypt(mysqli_real_escape_string($conn, $row['last_name']), ConfigHandler::get("app", "key")), mysqli_real_escape_string($conn, $session->getIP()), $iploc);
                                if (isset($_GET['r'])) {
                                    header('location: ' . $_GET['r']);
                                    $conn->close();
                                    die();
                                } else {
                                    header('location: /dashboard');
                                    $conn->close();
                                    die();
                                }
                            } else {
                                header('location: /auth/login?e=invalid_password');
                                $conn->close();
                                die();
                            }
                        } else {
                            header('location: /auth/login?e=invalid_email');
                            $conn->close();
                            die();
                        }
                    } else {
                        header('location: /auth/login?e=not_found_in_db');
                        $conn->close();
                        die();
                    }
                } else {
                    header('location: /auth/login?e=empty_form');
                    die();
                }
            } else {
                header('location: /auth/login?e=captcha_failed');
                die();
            }
        } else {
            header('location: /auth/login?e=csrf_failed');
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
    <script src="/dist/js/demo-theme.js"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <form class="card card-md" method="POST" action="/auth/login">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">
                        <?= $lang['login_welcome'] ?>
                        <?= ConfigHandler::get("app", "name") ?>
                    </h2>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['email'] ?>
                        </label>
                        <input type="email" name="email" required class="form-control"
                            placeholder="nayskutzu@mythicalsystems.me">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['password'] ?> (<a href="/auth/password/forgot" tabindex="-1">&nbsp;<?= $lang['forgot_password']?>&nbsp;</a>)
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" required class="form-control" placeholder="Password"
                                autocomplete="off">
                        </div>
                        

                    </div>
                    <?= $csrf->input('login-form'); ?>
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
                        <button type="submit" name="login" class="btn btn-primary w-100">
                            <?= $lang['login_login'] ?>
                        </button>
                    </div>
                    <div class="text-center text-muted mt-3">
                        <?= $lang['login_no_acc'] ?> <a href="/auth/register" tabindex="-1">
                            <?= $lang['register'] ?>
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