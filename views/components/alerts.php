<?php
use MythicalClient\App;

$lang = App::getLang();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['s']) && !$_GET['s'] == null) {
        //SUCCESS

        if ($_GET['s'] == "db_success") {
            ?>
            <div class="alert alert-outline-success d-flex align-items-center" role="alert">
                <span class="fas fa-check-circle text-success fs-3 me-3"></span>
                <p class="mb-0 flex-1">
                    <?= $lang['db_success'] ?>
                </p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        }
    }
    if (isset($_GET['w']) && !$_GET['w'] == null) {
        //WARNING
        ?>
        <div class="alert alert-outline-warning d-flex align-items-center" role="alert">
            <span class="fas fa-info-circle text-warning fs-3 me-3"></span>
            <p class="mb-0 flex-1">A simple primary alert—check it out!</p>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    }
    if (isset($_GET['e']) && !$_GET['e'] == null) {
        //ERROR
        if ($_GET['e'] == "pwd_no_match") {
            ?>
            <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
                <span class="fas fa-times-circle text-danger fs-3 me-3"></span>
                <p class="mb-0 flex-1">
                    <?= $lang['account_password_no_match'] ?>
                </p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        } else if ($_GET['e'] == "invalid_password") {
            ?>
                <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
                    <span class="fas fa-times-circle text-danger fs-3 me-3"></span>
                    <p class="mb-0 flex-1">
                    <?= $lang['invalid_password'] ?>
                    </p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
        } else if ($_GET['e'] == "email_exists") {
            ?>
                    <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
                        <span class="fas fa-times-circle text-danger fs-3 me-3"></span>
                        <p class="mb-0 flex-1">
                    <?= $lang['email_exists'] ?>
                        </p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            <?php
        } else if ($_GET['e'] == "db_error") {
            ?>
                        <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
                            <span class="fas fa-times-circle text-danger fs-3 me-3"></span>
                            <p class="mb-0 flex-1">
                    <?= $lang['db_error'] ?>
                            </p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
            <?php
        }

    }
}
?>