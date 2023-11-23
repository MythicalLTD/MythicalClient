<?php
use MythicalClient\Handlers\ConfigHandler;
?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link rel="shortcut icon" type="image/x-icon" href="<?= ConfigHandler::get("app","logo") ?>">
<link href="/dist/css/tabler.css" rel="stylesheet" />
<link href="/dist/css/tabler-flags.css" rel="stylesheet" />
<link href="/dist/css/tabler-payments.css" rel="stylesheet" />
<link href="/dist/css/tabler-vendors.css" rel="stylesheet" />
<link href="/dist/css/demo.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }
</style>
<link href="/dist/css/custom.css" rel="stylesheet" />
<link href="/dist/css/preloader.css" rel="stylesheet" />