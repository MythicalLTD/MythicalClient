<?php 
$router->add('/auth/register', function () {
    require("../views/auth/register.php");
});

$router->add('/auth/verify', function () {
    require("../views/auth/verify.php");
});
?>
