<?php 
require(__DIR__ . '/../requirements/page.php');
// Reset the key!
$bIsSession = $session->resetKey($_COOKIE['token']);
if ($bIsSession == true) {
    header('location: /account');
    die();
} else {
    header('location: /account?e=db_error');
    die();
}
?>