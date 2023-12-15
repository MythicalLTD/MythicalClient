<?php
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\Handlers\DatabaseHandler;
use MythicalClient\Handlers\EncryptionHandler;

$conn = DatabaseHandler::getConnection();
require(__DIR__ . '/requirements/page.php');

$sql = "SELECT * FROM users";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr" class="">

<head>
    <?php
    require(__DIR__ . '/requirements/head.php');
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
        include(__DIR__ . "/components/navbar.php");
        ?>
        <div class="content">
            <?php
            include(__DIR__ . "/components/alerts.php");
            ?>
            <div class="col-xl">
                <div class="card h-100">
                    <div class="card-body">
                        <h3>
                            <?= $lang['search_title'] ?>
                        </h3>
                        <p class="text-700">
                            <?= $lang['search_subtitle'] ?>
                        </p>
                        <div class="echart-revenue-target-conversion"
                            style="min-height: 230px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;"
                            _echarts_instance_="ec_1701442516973">
                            <div id="users">
                                <div class="search-box mb-3 mx-auto">
                                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                                        <input class="form-control search-input search form-control-sm" type="search"
                                            placeholder="<?= $lang['search'] ?>" aria-label="<?= $lang['search'] ?>" />
                                        <span class="fas fa-search search-box-icon"></span>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm fs--1 mb-0">
                                        <thead>
                                            <tr>
                                                <th class="sort border-top ps-3" data-sort="username">
                                                    <?= $lang['username'] ?>
                                                </th>
                                                <th class="sort border-top" data-sort="username">
                                                    <?= $lang['user_id'] ?>
                                                </th>
                                                <th class="sort border-top" data-sort="role">
                                                    <?= $lang['role'] ?>
                                                </th>
                                                <th class="sort border-top" data-sort="registered">
                                                    <?= $lang['registered'] ?>
                                                </th>
                                                <th class="sort text-end align-middle pe-0 border-top" scope="col">
                                                    <?= $lang['action'] ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td class="align-middle username ps-3">
                                                        <?= EncryptionHandler::decrypt($user['username'], ConfigHandler::get("app", "key")) ?>
                                                    </td>
                                                    <td class="align-middle user_id">
                                                        <code><?= EncryptionHandler::decrypt($user['user_id'], ConfigHandler::get("app", "key")) ?></code>
                                                    </td>
                                                    <td class="align-middle role">
                                                        <?= $user['role'] ?>
                                                    </td>
                                                    <td class="align-middle registred">
                                                        <?= $user['registred'] ?>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap text-end pe-0">
                                                        <a class="btn btn-primary btn-sm"
                                                            href="/profile/<?= EncryptionHandler::decrypt($user['user_id'], ConfigHandler::get("app", "key")) ?>">Profile</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between mt-3"><span class="d-none d-sm-inline-block"
                                        data-list-info="data-list-info"></span>
                                    <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span
                                                class="fas fa-chevron-left"></span></button>
                                        <ul class="mb-0 pagination"></ul><button class="page-link pe-0"
                                            data-list-pagination="next"><span
                                                class="fas fa-chevron-right"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include(__DIR__ . "/components/footer.php");
            ?>
        </div>
    </main>
    <?php require(__DIR__ . '/requirements/footer.php'); ?>
    <script>var options = {
            valueNames: ['username', 'user_id', 'role', 'registered']
        };

        var userList = new List('users', options);
    </script>
</body>

</html>