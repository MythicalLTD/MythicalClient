<?php 
$router->add('/errors/404', function () {
    require("../views/errors/404.php");
});

$router->add('/errors/403', function () {
    require("../views/errors/403.php");
});

$router->add('/errors/maintenance', function () {
    require("../views/errors/maintenance.php");
});

?>
