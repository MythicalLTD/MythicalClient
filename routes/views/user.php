<?php 
$router->add('/account', function () {
    require("../views/client/account/index.php");
});

$router->add('/account/reset/key', function () {
    require("../views/client/account/key_reset.php");
});

$router->add('/account/activity', function () {
    require("../views/client/account/activity.php");
});

$router->add('/profile/(.*)', function ($account_id) {
    require("../views/client/account/profile.php");
});

?>