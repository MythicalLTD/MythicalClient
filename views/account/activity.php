<?php
require(__DIR__.'/../requirements/page.php');

use MythicalClient\Handlers\ConfigHandler;
use MythicalCLient\Handlers\EncryptionHandler;

?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr" class="">

<head>
    <?php
    require(__DIR__.'/../requirements/head.php');
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
        include(__DIR__.'/../components/navbar.php');
        ?>
        <div class="content">
            <?php
            include(__DIR__.'/../components/alerts.php');
            ?>
            <div class="col-xl">
                <div class="card h-100">
                    <div class="card-body">
                        <h3>
                            <?= $lang['user_activity'] ?>
                        </h3>
                        <p class="text-700">
                            <?= $lang['user_activity_info'] ?>
                            <?= htmlspecialchars(EncryptionHandler::decrypt($session->getUserInfo("username"), ConfigHandler::get("app", "key"))) ?>
                        </p>
                        <div class="echart-revenue-target-conversion"
                            style="min-height: 230px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;"
                            _echarts_instance_="ec_1701442516973">
                            <div id="activity_table">
                                <div class="search-box mb-3 mx-auto">
                                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                                        <input class="form-control search-input search form-control-sm" type="search"
                                            placeholder="Search" aria-label="Search" />
                                        <span class="fas fa-search search-box-icon"></span>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm fs--1 mb-0">
                                        <thead>
                                            <tr>
                                                <th class="sort border-top ps-3" data-sort="username">Username</th>
                                                <th class="sort border-top" data-sort="description">Description</th>
                                                <th class="sort border-top" data-sort="action">Action</th>
                                                <th class="sort border-top" data-sort="time">Time</th>
                                                <th class="sort border-top" data-sort="ip_address">IP Address</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php
                                            // Assuming $activityManager is an instance of your ActivityManager class
                                            $activities = $ActivityManager->getActivities(EncryptionHandler::decrypt($session->getUserInfo("user_id"), ConfigHandler::get("app", "key")));

                                            foreach($activities as $activity) {
                                                ?>
                                                <tr>
                                                    <td class="align-middle ps-3 username">
                                                        <?= htmlspecialchars($activity['username']) ?>
                                                    </td>
                                                    <td class="align-middle description">
                                                        <?= htmlspecialchars($activity['description']) ?>
                                                    </td>
                                                    <td class="align-middle action">
                                                        <?= htmlspecialchars($activity['action']) ?>
                                                    </td>
                                                    <td class="align-middle time">
                                                        <?= htmlspecialchars($activity['time']) ?>
                                                    </td>
                                                    <td class="align-middle ip_address">
                                                        <?= htmlspecialchars($activity['ip_address']) ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
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
            include(__DIR__.'/../components/footer.php');
            ?>
        </div>
    </main>
    <?php require(__DIR__.'/../requirements/footer.php'); ?>
    <script>var options = {
            valueNames: ['username', 'description', 'action', 'time', 'ip_address']
        };

        var activityList = new List('activity_table', options);
    </script>
</body>

</html>