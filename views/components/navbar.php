<?php
use MythicalClient\Handlers\ConfigHandler;

?>
<nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarCombo" data-navbar-top="combo"
    data-move-target="#navbarVerticalNav">
    <div class="navbar-logo">
        <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse"
            aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                    class="toggle-line"></span></span></button>
        <a class="navbar-brand me-1 me-sm-3" href="/dashboard">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center"><img src="<?= ConfigHandler::get('app', 'logo') ?>"
                        alt="<?= ConfigHandler::get('app', 'name') ?>" width="27" />
                    <p class="logo-text ms-2 d-none d-sm-block">
                        <?= ConfigHandler::get('app', 'name') ?>
                    </p>
                </div>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse navbar-top-collapse order-1 order-lg-0 justify-content-center"
        id="navbarTopCollapse">
        <ul class="navbar-nav navbar-nav-top" data-dropdown-on-hover="data-dropdown-on-hover">
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle lh-1" href="#!" role="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                    aria-expanded="false"><span class="uil fs-0 me-2 uil-chart-pie"></span>Home</a>
                <ul class="dropdown-menu navbar-dropdown-caret">
                    <li><a class="dropdown-item active" href="/dashboard">
                            <div class="dropdown-item-wrapper"><span class="me-2 uil"
                                    data-feather="home"></span>Dashboard</div>
                        </a></li>
                    <li>
                </ul>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-nav-icons flex-row">
        <li class="nav-item">
            <div class="theme-control-toggle fa-icon-wait px-2"><input
                    class="form-check-input ms-0 theme-control-toggle-input" type="checkbox"
                    data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" /><label
                    class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon"
                        data-feather="moon"></span></label><label
                    class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon"
                        data-feather="sun"></span></label></div>
        </li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal"><span
                    data-feather="search" style="height:19px;width:19px;margin-bottom: 2px;"></span></a></li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell"
                    style="height:20px;width:20px;"></span></a>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret"
                id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                <div class="card position-relative border-0">
                    <div class="card-header p-2">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-black mb-0">Notificatons</h5><button
                                class="btn btn-link p-0 fs--1 fw-normal" type="button">Mark all as read</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="scrollbar-overlay" style="height: 5rem;">
                            <div class="border-200">
                                <div
                                    class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read border-bottom">
                                    <div class="d-flex align-items-center justify-content-between position-relative">
                                        <div class="d-flex">
                                            <div class="avatar avatar-m status-online me-3"><img class="rounded-circle"
                                                    src="https://avatars.githubusercontent.com/u/87282334?v=4"
                                                    alt="NaysKutzu" /></div>
                                            <div class="flex-1 me-sm-3">
                                                <h4 class="fs--1 text-black">NaysKutzu</h4>
                                                <p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span
                                                        class='me-1 fs--2'></span>Welcome to MythicalClient!</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="card-footer p-0 border-top border-0">
                  <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="pages/notifications.html">Notification history</a></div>
                </div>-->
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="navbarDropdownNindeDots" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false"><svg width="16" height="16"
                    viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                    <circle cx="2" cy="8" r="2" fill="currentColor"></circle>
                    <circle cx="2" cy="14" r="2" fill="currentColor"></circle>
                    <circle cx="8" cy="8" r="2" fill="currentColor"></circle>
                    <circle cx="8" cy="14" r="2" fill="currentColor"></circle>
                    <circle cx="14" cy="8" r="2" fill="currentColor"></circle>
                    <circle cx="14" cy="14" r="2" fill="currentColor"></circle>
                    <circle cx="8" cy="2" r="2" fill="currentColor"></circle>
                    <circle cx="14" cy="2" r="2" fill="currentColor"></circle>
                </svg></a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-nide-dots shadow border border-300"
                aria-labelledby="navbarDropdownNindeDots">
                <div class="card bg-white position-relative border-0">
                    <div class="card-body pt-3 px-3 pb-0 overflow-auto scrollbar" style="height: 11rem;">
                        <div class="row text-center align-items-center gx-0 gy-0">
                            <?php
                            if (!ConfigHandler::get("social_links", "twitter") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "twitter") ?>"><img
                                            src="assets/img/nav-icons/twitter.webp" alt="" width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Twitter</p>
                                    </a></div>
                                <?php
                            }
                            if (!ConfigHandler::get("social_links", "discord") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "discord") ?>"><img
                                            src="https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a69f118df70ad7828d4_icon_clyde_blurple_RGB.svg"
                                            alt="" width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Discord</p>
                                    </a></div>
                                <?php
                            }
                            if (!ConfigHandler::get("social_links", "guilded") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "guilded") ?>"><img
                                            src="https://img.guildedcdn.com/asset/Logos/logomark/Color/Guilded_Logomark_Color.png"
                                            alt="" width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Guilded</p>
                                    </a></div>
                                <?php
                            }
                            if (!ConfigHandler::get("social_links", "youtube") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "youtube") ?>"><img
                                            src="https://www.cdnlogo.com/logos/y/57/youtube-icon.svg" alt="" width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">YouTube</p>
                                    </a></div>
                                <?php
                            }
                            if (!ConfigHandler::get("social_links", "github") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "github") ?>"><img
                                            src="https://github.githubassets.com/assets/GitHub-Mark-ea2971cee799.png" alt=""
                                            width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">GitHub</p>
                                    </a></div>
                                <?php
                            }
                            if (!ConfigHandler::get("social_links", "reddit") == null) {
                                ?>
                                <div class="col-4"><a
                                        class="d-block hover-bg-200 p-2 rounded-3 text-center text-decoration-none mb-3"
                                        href="<?= ConfigHandler::get("social_links", "reddit") ?>"><img
                                            src="https://lingo-production.s3.amazonaws.com/thumbnails/070e6588-5353-4441-85d3-8af6108025b6/EFFzE1KmLil1WO3_ZqfHMwgNTRYLKqNwipEuVdUrgmI/480.png"
                                            alt="" width="30" />
                                        <p class="mb-0 text-black text-truncate fs--2 mt-1 pt-1">Reddit</p>
                                    </a></div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button"
                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-l ">
                    <img class="rounded-circle "
                        src="<?= $session->getUserInfo("avatar",TRUE) ?>"
                        alt="" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300"
                aria-labelledby="navbarDropdownUser">
                <div class="card position-relative border-0">
                    <div class="card-body p-0">
                        <div class="text-center pt-4 pb-3">
                            <div class="avatar avatar-xl ">
                                <img class="rounded-circle "
                                    src="<?= $session->getUserInfo("avatar", TRUE) ?>"
                                    alt="" />
                            </div>
                            <h5 class="mt-2 text-black">
                                <?= $session->getUserInfo("username", TRUE) ?>
                            </h5>
                            <h6 class="mt-2 text-black">
                                <?= $session->getUserInfo("first_name", TRUE) ?>,
                                <?= $session->getUserInfo("last_name", TRUE) ?>
                            </h6>
                        </div>
                    </div>
                    <div class="overflow-auto scrollbar" style="height: 6.80rem;">
                        <ul class="nav d-flex flex-column mb-2 pb-1">
                            <li class="nav-item"><a class="nav-link px-3" href="/account"> <span class="me-2 text-900"
                                        data-feather="user"></span><span>Profile</span></a></li>
                            <li class="nav-item"><a class="nav-link px-3" href="/account/activity"> <span
                                        class="me-2 text-900" data-feather="lock"></span>Activity</a></li>
                            <li class="nav-item"><a class="nav-link px-3" href="/account"> <span class="me-2 text-900"
                                        data-feather="settings"></span>Settings </a></li>
                        </ul>
                    </div>
                    <div class="card-footer p-0 border-top">
                        <!--<ul class="nav d-flex flex-column my-3">
                    <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="user-plus"></span>Add another account</a></li>
                  </ul>
                  <hr />--><br>
                        <div class="px-3"> <a class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                href="/auth/logout">
                                <span class="me-2" data-feather="log-out"> </span>Sign out</a></div>
                        <div class="my-2 text-center fw-bold fs--2 text-600"><a class="text-600 me-1" href="#!">Privacy
                                policy</a>&bull;<a class="text-600 mx-1" href="#!">Terms of Service</a></div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
<?php
include(__DIR__ . "/modals/search.php");
?>