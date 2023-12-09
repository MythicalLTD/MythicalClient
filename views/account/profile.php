<?php
require(__DIR__ . '/../requirements/page.php');

use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\Handlers\DatabaseConnectionHandler;

$conn = DatabaseConnectionHandler::getConnection();

if (isset($account_id) && !$account_id == null) {
    $safe_account_id = mysqli_real_escape_string($conn, $account_id);
    if ($session->doesUserExist($safe_account_id) == true) {
        
    } else {
        header('location: /404');
        die();
    }
} else {
    header('location: /404');
    die();
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
            <div class="pb-9">
                <div class="card mb-5">
                    <div class="card-header hover-actions-trigger d-flex justify-content-center align-items-end position-relative mb-7 mb-xxl-0"
                        style="min-height: 214px; ">
                        <div class="bg-holder rounded-top"
                            style="background-image:url(<?= ConfigHandler::get('app', 'background')?>);"></div>
                        <!--/.bg-holder-->
                        <input class="d-none" id="upload-porfile-picture" type="file" />
                        <div class="hoverbox feed-profile" style="width: 150px; height: 150px">
                            <div
                                class="position-relative bg-400 rounded-circle cursor-pointer d-flex flex-center mb-xxl-7">
                                <div class="avatar avatar-5xl">
                                    <img class="rounded-circle rounded-circle bg-white img-thumbnail shadow-sm"
                                        src="<?= $session->getUserInfoID($account_id, "avatar", TRUE) ?>" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-xl-between">
                            <div class="col-auto">
                                <div class="d-flex flex-wrap mb-3 align-items-center">
                                    <h2 class="me-2">
                                        <?= $session->getUserInfoID($safe_account_id, "username", TRUE) ?>
                                    </h2>
                                    <span class="fw-semi-bold fs-1 text-1100">
                                        (
                                        <?= $session->getUserInfoID($safe_account_id, "role", FALSE) ?> )
                                    </span>
                                </div>
                                <div class="mb-5">
                                    <div class="d-md-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="fa-solid fa-coins fs--1 text-700 me-2 me-lg-1 me-xl-2">
                                            </span>
                                            <a class="text-1100">
                                                <span class="fs-1 fw-bold text-600">
                                                    Coins:
                                                    <span class="fw-semi-bold ms-1 me-4">
                                                        <?= $session->getUserInfoID($safe_account_id, "coins", FALSE) ?>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="fa-solid fa-calendar fs--1 text-700 me-2 me-lg-1 me-xl-2">
                                            </span>
                                            <a class="text-1100">
                                                <span class="fs-1 fw-bold text-600">
                                                    Joinned:
                                                    <span class="fw-semi-bold ms-1 me-4">
                                                        <?= $session->getUserInfoID($safe_account_id, "registred", FALSE) ?>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row g-2">
                                    <div class="col-auto order-xxl-2"><button class="btn btn-primary lh-1">
                                            <span class="fa-solid fa-coins me-2"></span>Send Coins</button>
                                    </div>
                                    <div class="col-auto order-xxl-1"><button class="btn btn-phoenix-primary lh-1">
                                            <span class="fa-solid fa-user me-2"></span>Manager User</button>
                                    </div>
                                </div>
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