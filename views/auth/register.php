<?php
use MythicalClient\App;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EmailHandler;
use MythicalClient\Handlers\EncryptionHandler;
use MythicalClient\Managers\SessionManager;
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\CloudFlare\Turnstile;
use MythicalClient\Managers\SnowflakeManager;

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
    if (isset($_POST['register'])) {
        if ($csrf->validate('register-form')) {
            if (ConfigHandler::get("turnstile", "enabled") == "false") {
                $captcha_success = 1;
            } else {
                $captcha_success = Turnstile::validate_captcha($_POST["cf-turnstile-response"], $session->getIP(), ConfigHandler::get("turnstile", "secret_key"));
            }
            if ($captcha_success) {
                if (isset($_POST['first_name']) && !$_POST['first_name'] == null && isset($_POST['last_name']) && !$_POST['last_name'] == null && isset($_POST['username']) && !$_POST['username'] == null && isset($_POST['email']) && !$_POST['email'] == null && isset($_POST['password']) && !$_POST['password'] == null) {
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
                    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $password = mysqli_real_escape_string($conn, $_POST['password']);
                    $password_encrypted = password_hash($password, PASSWORD_BCRYPT);
                    $insecure_passwords = array("password", "1234", "qwerty", "letmein", "admin", "pass", "123456789", "kek");
                    if (in_array($password, $insecure_passwords)) {
                        header('location: /auth/register?e=blocked_password');
                        die();
                    }
                    $blocked_usernames = array("password", "1234", "qwerty", "letmein", "admin", "pass", "123456789", "Admin", "username", "Username");
                    if (in_array($username, $blocked_usernames)) {
                        header('location: /auth/register?e=blocked_username');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $username)) {
                        header('location: /auth/register?e=name_invalid');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $first_name)) {
                        header('location: /auth/register?e=name_invalid');
                        die();
                    }
                    if (preg_match("/[^a-zA-Z]+/", $last_name)) {
                        header('location: /auth/register?e=name_invalid');
                        die();
                    }
                    if (ConfigHandler::get("mailserver", "enabled") == "true") {
                        $code = mysqli_real_escape_string($conn, md5(rand()));
                    } else {
                        $code = null;
                    }
                    $check_query = "SELECT * FROM users WHERE username = '" . EncryptionHandler::encrypt($username, ConfigHandler::get("app", "key")) . "' OR email = '" . EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key")) . "'";
                    $result = mysqli_query($conn, $check_query);
                    if (!mysqli_num_rows($result) > 0) {
                        $aquery = "SELECT * FROM ip_logs WHERE ipaddr = '" . mysqli_real_escape_string($conn, $session->getIP()) . "'";
                        $aresult = mysqli_query($conn, $aquery);
                        $acount = mysqli_num_rows($aresult);
                        if (ConfigHandler::get("other", "allow_alts") == "false") {
                            if ($acount >= 1) {
                                header('location: /auth/register?e=alting');
                                die();
                            }
                        }
                        $vpn = false;
                        $response = file_get_contents("http://ip-api.com/json/" . $session->getIP() . "?fields=status,message,country,regionName,city,timezone,isp,org,as,mobile,proxy,hosting,query");
                        $response = json_decode($response, true);
                        if (isset($response['proxy'])) {
                            if ($response['proxy'] == true || $response['hosting'] == true) {
                                $vpn = true;
                            }
                        }
                        if ($response['type'] = !"Residential") {
                            $vpn = true;
                        }
                        if ($session->getIP() == "51.161.152.218" || $session->getIP() == "66.220.20.165") {
                            $vpn = false;
                        }
                        if (ConfigHandler::get("other", "allow_vpn") == "false") {
                            if ($vpn == true) {
                                header('location: /auth/register?e=vpn');
                                die();
                            }
                        }
                        $token = $session->createKey($username, $email);
                        $user_id = SnowflakeManager::getUniqueUserID();
                        $conn->query("INSERT INTO ip_logs (ipaddr, usertoken) VALUES ('" . mysqli_real_escape_string($conn, $session->getIP()) . "', '" . mysqli_real_escape_string($conn, $token) . "')");
                        $default = "https://www.gravatar.com/avatar/00000000000000000000000000000000";
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default);
                        if (!$code == null) {
                            if (EmailHandler::SendVerification($email,$code,$first_name,$last_name) == false) {
                                header("location: /auth/register?e=mailserver_down");
                                die();
                            } 
                        }
                        if ($session->createUser($username, $email, $first_name, $last_name, $password_encrypted, $grav_url, $user_id, $token, $session->getIP(), $code)) {
                            if (!$code == null) {
                                header('location: /auth/login?s=check_email');
                                $conn->close();
                                die();
                            } else {
                                header("location: /auth/login");
                                $conn->close();
                                die();
                            }
                        } else {
                            header('location: /auth/register?e=db_error');
                            $conn->close();
                            die();
                        }
                    } else {
                        header('location: /auth/register?e=username_or_email_exits');
                        die();
                    }
                } else {
                    header('location: /auth/register?e=empty_form');
                    die();
                }
            } else {
                header('location: /auth/register?e=captcha_failed');
                die();
            }
        } else {
            header('location: /auth/register?e=csrf_failed');
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
        <?= $lang['register'] ?>
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
            <form class="card card-md" method="POST" action="/auth/register">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">
                        <?= $lang['register_welcome'] ?>
                        <?= ConfigHandler::get("app", "name") ?>
                    </h2>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['first_name'] ?>
                        </label>
                        <input type="text" name="first_name" required class="form-control" placeholder="John">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['last_name'] ?>
                        </label>
                        <input type="text" required name="last_name" class="form-control" placeholder="Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['username'] ?>
                        </label>
                        <input type="text" required name="username" class="form-control" placeholder="NaysKutzu">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['email'] ?>
                        </label>
                        <input type="email" name="email" required class="form-control"
                            placeholder="nayskutzu@mythicalsystems.me">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <?= $lang['password'] ?>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" required class="form-control" placeholder="Password"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" required class="form-check-input" />
                            <span class="form-check-label">
                                <?= $lang['register_tos_1'] ?><a href="/terms-of-service" tabindex="-1">
                                    <?= $lang['terms'] ?>
                                </a>
                                <?= $lang['and'] ?> <a href="/privacy-policy" tabindex="-1">
                                    <?= $lang['privacy'] ?>
                                </a>.
                            </span>
                        </label>
                    </div>
                    <?= $csrf->input('register-form'); ?>
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
                        <button type="submit" name="register" class="btn btn-primary w-100">
                            <?= $lang['register_create_new_acc'] ?>
                        </button>
                    </div>
                    <div class="text-center text-muted mt-3">
                        <?= $lang['register_alr_have_an_acc'] ?> <a href="/auth/login" tabindex="-1">
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