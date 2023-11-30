<?php
namespace MythicalClient\Managers;

use MythicalClient\App;

class ServerModuleManager
{
    private $modules = [];
    private $enabledModules = [];
    private $disabledModules = [];
    private $cachePath = __DIR__ . '/../../caches/modules.json';

    /**
     * Constructor loads module cache on initialization.
     */
    public function __construct()
    {
        $this->createCacheFileIfNeeded();
        $this->loadModuleCache();
        $this->loadAllModules();
    }

    /**
     * Check if the cache file exists and create it if not.
     */
    private function createCacheFileIfNeeded()
    {
        $cacheDirectory = dirname($this->cachePath);

        // Check if the directory exists, and if not, create it
        if (!file_exists($cacheDirectory)) {
            mkdir($cacheDirectory, 0755, true);
        }

        // Check if the cache file exists and create it if not
        if (!file_exists($this->cachePath)) {
            $this->saveModuleCache(); // Create an empty cache file
        }
    }

    /**
     * Load module cache from JSON file.
     */
    private function loadModuleCache()
    {
        if (file_exists($this->cachePath)) {
            $cacheContent = file_get_contents($this->cachePath);
            $cacheData = json_decode($cacheContent, true);

            if (isset($cacheData['enabled'])) {
                $this->enabledModules = $cacheData['enabled'];
            }

            if (isset($cacheData['disabled'])) {
                $this->disabledModules = $cacheData['disabled'];
            }
        }
    }

    /**
     * Save module cache to JSON file.
     */
    private function saveModuleCache()
    {
        $cacheData = [
            'enabled' => $this->enabledModules,
            'disabled' => $this->disabledModules,
        ];

        file_put_contents($this->cachePath, json_encode($cacheData, JSON_PRETTY_PRINT));
    }

    /**
     * Load all modules from the modules directory.
     */
    private function loadAllModules()
    {
        $modulesPath = __DIR__ . "/../../modules/servers/";

        // Get all directories in the modules path
        $moduleDirectories = array_filter(glob($modulesPath . '*'), 'is_dir');

        // Load each module
        foreach ($moduleDirectories as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);

            // Load module only if it's not already loaded
            if (!isset($this->modules[$moduleName])) {
                $this->loadModule($moduleName);
            }
        }
    }

    /**
     * Load a module by name.
     *
     * @param string $moduleName
     * @return mixed The loaded module or an error string.
     */
    public function loadModule($moduleName)
    {
        $modulePath = __DIR__ . "/../../modules/servers/{$moduleName}/init.php";

        if (file_exists($modulePath)) {
            include_once $modulePath;

            $moduleClass = $moduleName;
            $module = new $moduleClass();
            $this->modules[$moduleName] = $module;

            // Check if module is enabled
            if (in_array($moduleName, $this->enabledModules)) {
                return $module;
            } else {
                return "Module is disabled: {$moduleName}";
            }
        } else {
            return "Module not found: {$moduleName}";
        }
    }

    /**
     * Enable a module.
     *
     * @param string $moduleName
     */
    public function enableModule($moduleName)
    {
        if (!in_array($moduleName, $this->enabledModules)) {
            $this->enabledModules[] = $moduleName;
            $this->saveModuleCache();
        }
    }

    /**
     * Disable a module.
     *
     * @param string $moduleName
     */
    public function disableModule($moduleName)
    {
        if (($key = array_search($moduleName, $this->enabledModules)) !== false) {
            unset($this->enabledModules[$key]);
            $this->disabledModules[] = $moduleName;
            $this->saveModuleCache();
        }
    }

    /**
     * Get information about all modules.
     *
     * @return array
     */
    public function getAllModulesInfo()
    {
        $modulesInfo = [];

        foreach ($this->modules as $moduleName => $module) {
            $modulePath = __DIR__ . "/../../modules/servers/{$moduleName}/MythicalClient.json";

            if (file_exists($modulePath)) {
                $moduleInfo = json_decode(file_get_contents($modulePath), true);

                // Add information about module enabled status
                $moduleInfo['enabled'] = in_array($moduleName, $this->enabledModules);
                $moduleInfo['moduleName'] = $moduleName;
                $modulesInfo[] = $moduleInfo;
            } else {
                // Handle missing mythicalclient.json
                App::Crash("MythicalClient.json not found for module: {$moduleName}");
            }
        }

        return $modulesInfo;
    }

    /**
     * Execute all loaded modules.
     */
    public function executeModules()
    {
        foreach ($this->modules as $moduleName => $module) {
            $module->execute();
        }
    }

    /**
     * Execute a specific action for a module.
     *
     * @param string $moduleName
     * @param string $action
     * @param array $config
     */
    public function executeAction($moduleName, $action, $config)
    {
        if (isset($this->modules[$moduleName])) {
            $this->modules[$moduleName]->executeAction($action, $config);
        } else {
            App::Crash("Module not loaded: {$moduleName}");
        }
    }
}