<?php 
$router->add('/account', function () {
    require("../views/account/index.php");
});

$router->add('/account/reset/key', function () {
    require("../views/account/key_reset.php");
});

?>