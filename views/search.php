<?php
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EncryptionHandler;

$conn = DatabaseConnectionHandler::getConnection();
require(__DIR__ . '/requirements/page.php');

$sql = "SELECT * FROM users";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

$conn->close();

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
                        <h3>Products</h3>
                        <p class="text-700">List of the prodcuts you own!</p>
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
                                                <th class="sort border-top ps-3" data-sort="avatar">Avatar</th>
                                                <th class="sort border-top ps-3" data-sort="username">Username</th>
                                                <th class="sort border-top" data-sort="role">Role</th>
                                                <th class="sort border-top" data-sort="registered">Registered</th>
                                                <th class="sort text-end align-middle pe-0 border-top" scope="col">
                                                    ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td class="align-middle ps-3 avatar"><img src="<?= EncryptionHandler::decrypt($user['avatar'],ConfigHandler::get("app","key")) ?>"
                                                            alt="User Avatar" class="rounded-circle avatar-image"></td>
                                                    <td class="align-middle username">
                                                        <?= EncryptionHandler::decrypt($user['username'],ConfigHandler::get("app","key")) ?>
                                                    </td>
                                                    <td class="align-middle role">
                                                        <?= $user['role'] ?>
                                                    </td>
                                                    <td class="align-middle registred">
                                                        <?= $user['registred'] ?>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap text-end pe-0">
                                                        <!-- Your action buttons or dropdown here -->
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
            valueNames: ['name', 'cost', 'status']
        };

        var userList = new List('users', options);
    </script>
</body>

</html>