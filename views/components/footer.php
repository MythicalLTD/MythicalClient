<?php 
use MythicalClient\Handlers\ConfigHandler;
?>
<footer class="footer position-absolute">
    <div class="row g-0 justify-content-between align-items-center h-100">
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 mt-2 mt-sm-0 text-900">Copyright (C) <a class="mx-1"
                    href="https://github.com/mythicalltd/mythicalclient">MythicalSystems</a><span
                    class="d-none d-sm-inline-block"></span> </span><br class="d-sm-none" />2022-2023</p>
        </div>
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-600">v
                <?= ConfigHandler::get("static", "version") ?>
                <code><?= ConfigHandler::get("static", "channel") ?></code>
            </p>
        </div>
    </div>
</footer>