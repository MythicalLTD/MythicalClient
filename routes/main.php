<?php 
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\App;

$router->add('/', function() {
    if (isset($_GET['e'])) {
        header('location: /dashboard?e='. $_GET['e']);
    } else if (isset($_GET['s'])) {
        header('location: /dashboard?s='. $_GET['s']);
    } else {
        $template = file_get_contents('../templates/landing/index.html');
        $placeholders = array('%APP_LOGO%', '%APP_NAME%', '%APP_URL%', '%APP_BG%', '%SEO_TITLE%','%SEO_DESCRIPTION%','%SEO_IMAGE%','%SEO_KEYWORDS%','%DISCORD_INVITE%',"%LANDING_DESCRIPTION%",'%LANDING_SUPPORT_EMAIL%','%FEEDBACK_1_NAME%' ,'%FEEDBACK_1_ROLE%' ,'%FEEDBACK_1_AVATAR%' ,'%FEEDBACK_1_DESCRIPTION%', '%FEEDBACK_2_NAME%', '%FEEDBACK_2_ROLE%', '%FEEDBACK_2_AVATAR%', '%FEEDBACK_2_DESCRIPTION%' ,'%FEEDBACK_3_NAME%' ,'%FEEDBACK_3_ROLE%' ,'%FEEDBACK_3_AVATAR%' ,'%FEEDBACK_3_DESCRIPTION%');
        $values = array(ConfigHandler::get("app","logo"),ConfigHandler::get("app","name"),App::getUrl(),ConfigHandler::get("app","background"),ConfigHandler::get("seo","title"),ConfigHandler::get("seo","description"),ConfigHandler::get("seo","image"),ConfigHandler::get("seo","keywords"),ConfigHandler::get("discord","invite"),ConfigHandler::get("landingpage","description"),ConfigHandler::get("landingpage","support_email"),ConfigHandler::get("landingpage","FEEDBACK_1_NAME"), ConfigHandler::get("landingpage","FEEDBACK_1_ROLE"),ConfigHandler::get("landingpage","FEEDBACK_1_AVATAR"),ConfigHandler::get("landingpage","FEEDBACK_1_DESCRIPTION"), ConfigHandler::get("landingpage","FEEDBACK_2_NAME"), ConfigHandler::get("landingpage","FEEDBACK_2_ROLE"),ConfigHandler::get("landingpage","FEEDBACK_2_AVATAR"),ConfigHandler::get("landingpage","FEEDBACK_2_DESCRIPTION"), ConfigHandler::get("landingpage","FEEDBACK_3_NAME"), ConfigHandler::get("landingpage","FEEDBACK_3_ROLE"),ConfigHandler::get("landingpage","FEEDBACK_3_AVATAR"),ConfigHandler::get("landingpage","FEEDBACK_3_DESCRIPTION"));
        $templateView = str_replace($placeholders, $values, $template);
        die($templateView);
    }
});

try {
    $router->add('/dashboard', function () {
        require("../views/dashboard.php");
    });
} catch (Exception $ex) {
    App::Crash("Failed to build frontend: ".$ex);
    die();
}

try {
    $router->add('/test', function () {
        require("../views/test.php");
    });
} catch (Exception $ex) {
    App::Crash("Failed to build frontend: ".$ex);
    die();
}
?>