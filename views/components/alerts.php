<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['s']) && !$_GET['s'] == null) {
        //SUCCESS
        ?>
        <div class="alert alert-outline-success d-flex align-items-center" role="alert">
            <span class="fas fa-check-circle text-success fs-3 me-3"></span>
            <p class="mb-0 flex-1">A simple primary alert—check it out!</p>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
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
        ?>
        <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
            <span class="fas fa-times-circle text-danger fs-3 me-3"></span>
            <p class="mb-0 flex-1">A simple danger alert—check it out!</p>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    }
}
?>