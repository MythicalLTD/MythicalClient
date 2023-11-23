<?php 
/**
 * MythicalClient - Addon Manager
 * 
 * This is the system to addons inside the dash
 * 
 */
namespace MythicalClient\Managers;

class AddonsManager
{
    private $addonsPath;
    private $loadedAddons = [];

    public function __construct($addonsPath = '../addons') {
        $this->addonsPath = __DIR__ . "/$addonsPath";
    }
    /**
     * Load addons from the addon folder
     */
    public function loadAddons() {
        $addonFolders = glob($this->addonsPath . '/*', GLOB_ONLYDIR);

        foreach ($addonFolders as $addonFolder) {
            $addonDetailsFile = $addonFolder . '/init.php';

            if (file_exists($addonDetailsFile)) {
                $addonDetails = include $addonDetailsFile;

                if (is_array($addonDetails) && !empty($addonDetails)) {
                    $this->loadedAddons[] = [
                        'details' => $addonDetails,
                        'folder' => $addonFolder,
                    ];
                }
            }
        }

        return $this->loadedAddons;
    }
    /**
     * Add the addons to the route system
     * 
     * @param string $router The router
     */
    public function processAddonRoutes($router) {
        foreach ($this->loadedAddons as $addon) {
            $routesFile = $addon['folder'] . '/routes.php';

            if (file_exists($routesFile)) {
                $addonRoutes = include $routesFile;

                foreach ($addonRoutes as $route) {
                    $router->add($route['path'], function () use ($route, $addon) {
                        $viewFile = $addon['folder'] . '/view/' . $route['view'];

                        if (file_exists($viewFile)) {
                            require $viewFile;
                        } else {
                            header("location: /404");
                        }
                    });
                }
            }
        }
    }
}
?>