<?php
use MythicalClient\Handlers\ConfigHandler;
require(__DIR__ . '/requirements/page.php');
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
      <div class="pb-5">
        <div class="row g-4">
          <div class="col-12 col-xxl-6">
            <div class="mb-8">
              <h2 class="mb-2">Dashboard</h2>
              <h5 class="text-700 fw-semi-bold">Here’s what’s going on at your products!</h5>
            </div>
            <div class="row align-items-center g-4">
              <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center"><span class="fa-stack"
                    style="min-height: 46px;min-width: 46px;"><span
                      class="fa-solid fa-square fa-stack-2x text-success-300"
                      data-fa-transform="down-4 rotate--10 left-4"></span><span
                      class="fa-solid fa-circle fa-stack-2x stack-circle text-success-100"
                      data-fa-transform="up-4 right-3 grow-2"></span><span
                      class="fa-stack-1x fa-solid fa-star text-success "
                      data-fa-transform="shrink-2 up-8 right-6"></span></span>
                  <div class="ms-3">
                    <h4 class="mb-0">57 new orders</h4>
                    <p class="text-800 fs--1 mb-0">Awating processing</p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center"><span class="fa-stack"
                    style="min-height: 46px;min-width: 46px;"><span
                      class="fa-solid fa-square fa-stack-2x text-warning-300"
                      data-fa-transform="down-4 rotate--10 left-4"></span><span
                      class="fa-solid fa-circle fa-stack-2x stack-circle text-warning-100"
                      data-fa-transform="up-4 right-3 grow-2"></span><span
                      class="fa-stack-1x fa-solid fa-pause text-warning "
                      data-fa-transform="shrink-2 up-8 right-6"></span></span>
                  <div class="ms-3">
                    <h4 class="mb-0">5 orders</h4>
                    <p class="text-800 fs--1 mb-0">On hold</p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center"><span class="fa-stack"
                    style="min-height: 46px;min-width: 46px;"><span
                      class="fa-solid fa-square fa-stack-2x text-danger-300"
                      data-fa-transform="down-4 rotate--10 left-4"></span><span
                      class="fa-solid fa-circle fa-stack-2x stack-circle text-danger-100"
                      data-fa-transform="up-4 right-3 grow-2"></span><span
                      class="fa-stack-1x fa-solid fa-xmark text-danger "
                      data-fa-transform="shrink-2 up-8 right-6"></span></span>
                  <div class="ms-3">
                    <h4 class="mb-0">15 products</h4>
                    <p class="text-800 fs--1 mb-0">Out of stock</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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
                  <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input
                      class="form-control search-input search form-control-sm" type="search" placeholder="<?= $lang['search']?>"
                      aria-label="<?= $lang['search']?>" />
                    <span class="fas fa-search search-box-icon"></span>
                  </form>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped table-sm fs--1 mb-0">
                    <thead>
                      <tr>
                        <th class="sort border-top ps-3" data-sort="name">Product/Service</th>
                        <th class="sort border-top" data-sort="cost">Cost</th>
                        <th class="sort border-top" data-sort="status">Status</th>
                        <th class="sort text-end align-middle pe-0 border-top" scope="col">ACTION</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                      <tr>
                        <td class="align-middle ps-3 name">Anal SEX (6h)</td>
                        <td class="align-middle cost">149€</td>
                        <td class="align-middle status">Expired</td>
                        <td class="align-middle white-space-nowrap text-end pe-0">
                          <div class="font-sans-serif btn-reveal-trigger position-static"><button
                              class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2"
                              type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                              aria-expanded="false" data-bs-reference="parent"><span
                                class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item"
                                href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="align-middle ps-3 name">Oral SEX (6h)</td>
                        <td class="align-middle cost">1,249€</td>
                        <td class="align-middle status">Expired</td>
                        <td class="align-middle white-space-nowrap text-end pe-0">
                          <div class="font-sans-serif btn-reveal-trigger position-static"><button
                              class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2"
                              type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                              aria-expanded="false" data-bs-reference="parent"><span
                                class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item"
                                href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="align-middle ps-3 name">BlowJob (6h)</td>
                        <td class="align-middle cost">149€</td>
                        <td class="align-middle status">Expired</td>
                        <td class="align-middle white-space-nowrap text-end pe-0">
                          <div class="font-sans-serif btn-reveal-trigger position-static"><button
                              class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2"
                              type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                              aria-expanded="false" data-bs-reference="parent"><span
                                class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item"
                                href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="d-flex justify-content-between mt-3"><span class="d-none d-sm-inline-block"
                    data-list-info="data-list-info"></span>
                  <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span
                        class="fas fa-chevron-left"></span></button>
                    <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span
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