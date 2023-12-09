<?php 
$router->add('/account', function () {
    require("../views/account/index.php");
});

$router->add('/account/reset/key', function () {
    require("../views/account/key_reset.php");
});

$router->add('/account/activity', function () {
    require("../views/account/activity.php");
});

$router->add('/profile/(.*)', function ($account_id) {
    require("../views/account/profile.php");
});

?>