<?php
use MythicalClient\Handlers\ConfigHandler;

?>
<!-- ===============================================-->
<!--
    Hello! why hello?
-->
<!-- ===============================================-->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ===============================================-->
<!--    Favicons-->
<!-- ===============================================-->
<link rel="apple-touch-icon" sizes="180x180" href="<?= ConfigHandler::get("app","logo")?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= ConfigHandler::get("app","logo")?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= ConfigHandler::get("app","logo")?>">
<link rel="shortcut icon" type="image/x-icon" href="<?= ConfigHandler::get("app","logo")?>">
<meta name="msapplication-TileImage" content="<?= ConfigHandler::get("app","logo")?>">
<meta name="theme-color" content="#ffffff">
<script src="/vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="/vendors/simplebar/simplebar.min.js"></script>
<script src="/assets/js/config.js"></script>

<!-- ===============================================-->
<!--    Stylesheets-->
<!-- ===============================================-->
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
<link href="/vendors/simplebar/simplebar.min.css" rel="stylesheet">
<link rel="/stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link href="/assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
<link href="/assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
<link href="/assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
<link href="/assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
<script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
</script>
<link href="/vendors/leaflet/leaflet.css" rel="stylesheet">
<link href="/vendors/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
<link href="/vendors/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">