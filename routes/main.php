<?php 
$router->add('/', function() {
    if (isset($_GET['e'])) {
        header('location: /dashboard?e='. $_GET['e']);
    } else if (isset($_GET['s'])) {
        header('location: /dashboard?s='. $_GET['s']);
    } else {
        header('location: /dashboard');
    }
});

$router->add('/dashboard', function () {
    require("../views/index.php");
});
?>