<?php 
$router->add('/auth/register', function () {
    require("../views/client/auth/register.php");
});

$router->add('/auth/verify', function () {
    require("../views/client/auth/verify.php");
});

$router->add('/auth/login', function () {
    require("../views/client/auth/login.php");
});

$router->add('/auth/logout', function () {
    require("../views/client/auth/logout.php");
});

$router->add('/auth/password/forgot', function () {
    require("../views/client/auth/forgot.php");
});

$router->add('/auth/password/reset', function () {
    require("../views/client/auth/reset.php");
});
?>
