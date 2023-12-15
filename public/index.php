<?php
/**
 * MythicalClient - Index File
 * 
 * This file is the core file of MythicalClient
 * 
 * By any chance, do not edit, modify, or even remove this file if you do not know what you are doing!
 * This is a file that can break your client area and should not be modified if you are not an experienced developer!
 * 
 * Copyright 2023 MythicalSystems 
 * Copyright 2023 NaysKutzu
 */

// Load dependencies and set up error handling
try {
    if (file_exists('../vendor/autoload.php')) {
        require("../vendor/autoload.php");
    } else {
        die('Hello, it looks like you did not run: "<code>composer install --no-dev --optimize-autoloader</code>". Please run that and refresh the page');
    }
} catch (Exception $e) {
    die('Hello, it looks like you did not run: <code>composer install --no-dev --optimize-autoloader</code> Please run that and refresh');
}

use MythicalClient\App;
use MythicalClient\Handlers\ConfigHandler;

/**
 * Check if the client area has access to the directory!
 */
if (!is_writable(__DIR__)) {
    App::Crash("We have no access to our client directory. Open the terminal and run: chown -R www-data:www-data /var/www/MythicalClient/*");#
    die();
}

/**
 * Check if app is running on https
 */
if (!App::isHTTPS()) {
    App::Crash("We are sorry, but the dash can only run on HTTPS, not HTTP.");
    die();
}

/**
 * System to check for debug mode!
 */
if (!ConfigHandler::get("app", "debug") == null && ConfigHandler::get("app", "debug" == "true")) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else if (!ConfigHandler::get("app", "debug") == null && ConfigHandler::get("app", "debug" == "true")) {
    return null;
} else {
    App::Crash("We are sorry, but your configuration file is missing the required values!");
    die();
}

/**
 * System to load the languages
 */

$lang = App::getLang();

/**
 * Check if encryption works on this system
 * 
 */
if (!extension_loaded('openssl')) {
    App::Crash("The OpenSSL extension is not installed. Please install it for secure encryption.");
    die();
}

$minKeyLength = 32;
if (strlen(ConfigHandler::get("app", "key")) < $minKeyLength) {
    App::Crash("The encryption key is too short. Please use a key of at least ' . $minKeyLength . ' characters for better security.");
    die();
}

/**
 * MythicalClient maintenance manager
 * 
 * This is the system that checks if the app has the maintenance mod enabled
 * if yes it will show the maintain page
 */
if (ConfigHandler::get('app', 'maintenance') == "true") {
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/maintenance.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['maintenance'], $lang['error_maintenance_title'], $lang['error_maintenance_subtitle']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
} 

/**
 * MythicalClient Router System
 * 
 * This is the route system for mythicalclient that includes all the routes to the app!
 */
$router = new \Router\Router();
//Routes for view
$routesViewDirectory = __DIR__ . '/../routes/views/';
$phpViewFiles = glob($routesViewDirectory . '*.php');
foreach ($phpViewFiles as $phpViewFile) {
    try {
        http_response_code(200);
        include $phpViewFile;
    } catch (Exception $ex) {
        http_response_code(500);
        App::Crash("Failed to start app: " . $e->getMessage());
        die();
    }
    
}

$router->add("/(.*)", function () {
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/404.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['error_title'], $lang['error_404'], $lang['error_to_home']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
});

//Routes for API
$routesAPIDirectory = __DIR__ . '/../routes/api/';
$phpApiFiles = glob($routesAPIDirectory . '*.php');
foreach ($phpApiFiles as $phpApiFile) {
    try {
        include $phpApiFile;
    } catch (Exception $ex) {
        header('Content-type: application/json');
        ini_set("display_errors", 0);
        ini_set("display_startup_errors", 0);
        $rsp = array(
            "code" => 500,
            "error" => "The server encountered a situation it doesn't know how to handle.",
            "message" => "We are sorry, but our server can't handle this request. Please do not try again!"
        );
        http_response_code(500);
        die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
}

$router->add("/api/(.*)", function () {
    require("../api/errors/404.php");
});



try {
    $router->route();
} catch (Exception $e) {
    App::Crash("Failed to start app: " . $e->getMessage());
    die();
}
?>