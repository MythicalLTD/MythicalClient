<?php 
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\App;


$router->add('/errors/404', function() {
    http_response_code(404);
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/404.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['error_title'], $lang['error_404'], $lang['error_to_home']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
});


$router->add('/errors/403', function () {
    http_response_code(403);
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/403.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['error_title'], $lang['error_403'], $lang['error_to_home']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
});

$router->add('/errors/500', function () {
    http_response_code(500);
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/500.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['error_title'], $lang['error_500'], $lang['error_to_home']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
});

$router->add('/errors/maintenance', function() {
    http_response_code(403);
    $lang = App::getLang();
    $template = file_get_contents('../templates/error/maintenance.html');
    $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%LANG_VAL1%', '%LANG_VAL2%', '%LANG_VAL3%');
    $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),$lang['maintenance'], $lang['error_maintenance_title'], $lang['error_maintenance_subtitle']);
    $templateView = str_replace($placeholders, $values, $template);
    die($templateView);
});

?>
