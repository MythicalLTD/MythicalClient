<?php
use MythicalClient\Handlers\DatabaseHandler;
if (isset($_COOKIE['token']) && !$_COOKIE['token'] == null) {
    header('location: /dashboard');
    die();
}

$conn = DatabaseHandler::getConnection();

if (isset($_GET['code']) && !$_GET['code'] == null) {
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    $query = "SELECT * FROM users WHERE verification_code = '$code'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $conn->query("UPDATE `users` SET `verification_code` = NULL WHERE `users`.`verification_code` = '" . $code . "';");
            $conn->close();
            header('location: /auth/login?s=email_success');
            die();
        } else {
            header("location: /auth/login?e=not_found_in_db");
            die();
        }
    } else {
        header("location: /auth/login?e=not_found_in_db");
        die();
    }
} else {
    header('location: /auth/login?e=email_code_wrong');
    die();
}
?>