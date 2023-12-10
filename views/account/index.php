<?php
require(__DIR__ . '/../requirements/page.php');

use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EncryptionHandler;

$conn = DatabaseConnectionHandler::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit']) && !$_POST['submit'] == null) {
        if (
            isset($_POST['first_name']) &&
            isset($_POST['last_name']) &&
            isset($_POST['email']) &&
            isset($_POST['password_new']) &&
            isset($_POST['password_confirm'])
        ) {
            if (
                $_POST['first_name'] !== null &&
                $_POST['last_name'] !== null &&
                $_POST['email'] !== null &&
                $_POST['password_new'] !== null &&
                $_POST['password_confirm'] !== null
            ) {
                $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
                $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = mysqli_real_escape_string($conn, $_POST['password_new']);
                $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

                if (!$password == null) {
                    if ($password == $password_confirm) {
                        $password = password_hash($password, PASSWORD_BCRYPT);
                    } else {
                        header('location: /account?e=pwd_no_match');
                        die();
                    }
                } else {
                    $password = null;
                }

                if (!$email == $session->getUserInfo("email",TRUE)) {
                    $check_query = "SELECT * FROM users WHERE email = '" . EncryptionHandler::encrypt($email, ConfigHandler::get('app', 'key')) . "'";
                    $result = mysqli_query($conn, $check_query);
                    if (!mysqli_num_rows($result) > 0) {

                    } else {
                        header('location: /account?e=email_exists');
                        die();
                    }
                } else {
                    $email = null;
                }
                $account = $session->updateAccount($_COOKIE['token'], $first_name, $last_name, $email, $password);
                if ($account) {
                    header('location: /account?s=db_success');
                    die();
                } else {
                    header('location: /account?e=db_error');
                    die();
                }
            } else {
                header('location: /account?empty_form');
                die();
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr" class="">

<head>
    <?php
    require(__DIR__ . '/../requirements/head.php');
    ?>
    <title>
        <?= ConfigHandler::get('app', 'name') ?>
    </title>

</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <?php
        include(__DIR__ . '/../components/navbar.php');
        ?>
        <div class="content">
            <?php
            include(__DIR__ . '/../components/alerts.php');
            ?>
            <div class="mb-9">
                <div class="row g-6">
                    <div class="col-12 col-xl-4">
                        <div class="card mb-5">
                            <div class="card-header hover-actions-trigger position-relative mb-6"
                                style="min-height: 130px; ">
                                <div class="bg-holder rounded-top"
                                    style="background-image: linear-gradient(0deg, #000000 -3%, rgba(0, 0, 0, 0) 83%), url(<?= ConfigHandler::get('app', 'background') ?>)">
                                    <input class="d-none" id="upload-settings-cover-image" type="file" /><label
                                        class="cover-image-file-input" for="upload-settings-cover-image"></label>
                                    <div class="hover-actions end-0 bottom-0 pe-1 pb-2 text-white"><span
                                            class="fa-solid fa-camera me-2"></span></div>
                                </div><input class="d-none" id="upload-settings-porfile-picture" type="file" /><label
                                    class="avatar avatar-4xl status-online feed-avatar-profile cursor-pointer"
                                    for="upload-settings-porfile-picture"><img
                                        class="rounded-circle img-thumbnail bg-white shadow-sm"
                                        src="<?= $session->getUserInfo("avatar",TRUE) ?>"
                                        width="200" alt="" /></label>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap mb-2 align-items-center">
                                            <h3 class="me-2">
                                                <?= $session->getUserInfo("first_name",TRUE) ?>
                                                <?= $session->getUserInfo("last_name",TRUE) ?>
                                            </h3><span class="fw-normal fs-0">
                                                <?= $session->getUserInfo("username",TRUE) ?>
                                            </span>
                                        </div>
                                        <div class="d-flex d-xl-block d-xxl-flex align-items-center">
                                            <div class="d-flex mb-xl-2 mb-xxl-0"><span
                                                    class="fa-solid fa-user-check fs--2 me-2 me-lg-1 me-xl-2"></span>
                                                <h6 class="d-inline-block mb-0">
                                                    <?= $session->getUserInfo('coins',TRUE) ?><span
                                                        class="fw-semi-bold ms-1 me-4">
                                                        <?= $lang['balance'] ?>
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="border-bottom border-300 mb-4">
                            <form method="POST" action="/account">
                                <div class="mb-6">
                                    <h4 class="mb-4">
                                        <?= $lang['account_personal_info'] ?>
                                    </h4>
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-icon-container">
                                                <div class="form-floating">
                                                    <input class="form-control form-icon-input" required
                                                        name="first_name" type="text"
                                                        value="<?= $session->getUserInfo("first_name",TRUE) ?>"
                                                        placeholder="<?= $session->getUserInfo("first_name",TRUE) ?>" />
                                                    <label class="text-700 form-icon-label" for="firstName">
                                                        <?= strtoupper($lang['first_name']) ?>
                                                    </label>
                                                </div>
                                                <span class="fa-solid fa-user text-900 fs--1 form-icon"></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-icon-container">
                                                <div class="form-floating">
                                                    <input class="form-control form-icon-input" type="text" required
                                                        name="last_name"
                                                        value="<?= $session->getUserInfo("last_name",TRUE) ?>"
                                                        placeholder="<?= $session->getUserInfo("last_name",TRUE) ?>" />
                                                    <label class="text-700 form-icon-label" for="lastName">
                                                        <?= strtoupper($lang['last_name']) ?>
                                                    </label>
                                                </div>
                                                <span class="fa-solid fa-user text-900 fs--1 form-icon"></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-icon-container">
                                                <div class="form-floating">
                                                    <input class="form-control form-icon-input" required name="email"
                                                        type="email"
                                                        placeholder="<?= $session->getUserInfo("email",TRUE) ?>"
                                                        value="<?= $session->getUserInfo("email",TRUE) ?>" />
                                                    <label class="text-700 form-icon-label" for="emailSocial">
                                                        <?= strtoupper($lang['email']) ?>
                                                    </label>
                                                </div>
                                                <span class="fa-solid fa-envelope text-900 fs--1 form-icon"></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-icon-container">
                                                <div class="form-floating">
                                                    <input class="form-control form-icon-input" required name="id"
                                                        type="text"
                                                        readonly
                                                        disabled
                                                        placeholder="<?= $session->getUserInfo("user_id",TRUE) ?>"
                                                        value="<?= $session->getUserInfo("user_id",TRUE) ?>" />
                                                    <label class="text-700 form-icon-label" for="id">
                                                        <?= strtoupper($lang['account_id']) ?>
                                                    </label>
                                                </div>
                                                <span class="fa-solid fa-user text-900 fs--1 form-icon"></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-icon-container">
                                                <div class="form-floating">
                                                    <input class="form-control form-icon-input" required name="token"
                                                        type="text"
                                                        readonly
                                                        disabled
                                                        placeholder="<?= $session->getUserInfo("token",FALSE) ?>"
                                                        value="<?= $session->getUserInfo("token",FALSE) ?>" />
                                                    <label class="text-700 form-icon-label" for="token">
                                                        <?= strtoupper($lang['account_token']) ?>
                                                    </label>
                                                </div>
                                                <span class="fa-solid fa-key text-900 fs--1 form-icon"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-6 gy-6 gy-sm-3">
                                    <div class="col-12 col-sm-6">
                                        <h4 class="mb-4">
                                            <?= $lang['change_password'] ?>
                                        </h4>
                                        <div class="form-icon-container mb-3">
                                            <div class="form-floating">
                                                <input class="form-control form-icon-input" name="password_new"
                                                    id="newPassword" type="password" placeholder="New password" />
                                                <label class="text-700 form-icon-label" for="newPassword">
                                                    <?= $lang['account_new_password'] ?>
                                                </label>
                                            </div>
                                            <span class="fa-solid fa-key text-900 fs--1 form-icon"></span>
                                        </div>
                                        <div class="form-icon-container">
                                            <div class="form-floating">
                                                <input class="form-control form-icon-input" name="password_confirm"
                                                    id="newPassword2" type="password"
                                                    placeholder="Confirm New password" />
                                                <label class="text-700 form-icon-label" for="newPassword2">
                                                    <?= $lang['account_confirm_new_password'] ?>
                                                </label>
                                            </div>
                                            <span class="fa-solid fa-key text-900 fs--1 form-icon"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mb-6">
                                    <div>
                                        <a href="/account" class="btn btn-phoenix-secondary me-2">
                                            <?= $lang['cancel_changes'] ?>
                                        </a>
                                        <button type="submit" name="submit" value="true"
                                            class="btn btn-phoenix-primary">
                                            <?= $lang['save_information'] ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row gy-5">
                            <div class="col-12 col-md-6">
                                <h4 class="text-black">
                                    <?= $lang['account_key_reest_title'] ?>
                                </h4>
                                <p class="text-700">
                                    <?= $lang['account_key_reset_info'] ?>
                                </p>
                                <a href="/account/reset/key" class="btn btn-phoenix-warning">
                                    <?= $lang['reset'] ?>
                                </a>
                            </div>
                            <div class="col-12 col-md-6">
                                <h4 class="text-black">
                                    <?= $lang['account_delete_title'] ?>
                                </h4>
                                <p class="text-700">
                                    <?= $lang['account_delete_info'] ?>
                                </p>
                                <a href="/account/delete" class="btn btn-phoenix-danger">
                                    <?= $lang['account_delete_button'] ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            include(__DIR__ . '/../components/footer.php');
            ?>
        </div>
    </main>
    <?php require(__DIR__ . '/../requirements/footer.php'); ?>
</body>

</html>