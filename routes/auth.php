<?php 
$router->add('/auth/register', function () {
    require("../views/auth/register.php");
});

$router->add('/auth/verify', function () {
    require("../views/auth/verify.php");
});

$router->add('/auth/login', function () {
    require("../views/auth/login.php");
});

$router->add('/auth/logout', function () {
    require("../views/auth/logout.php");
});

$router->add('/auth/password/forgot', function () {
    require("../views/auth/forgot.php");
});

$router->add('/auth/password/reset', function () {
    require("../views/auth/reset.php");
});
?>
