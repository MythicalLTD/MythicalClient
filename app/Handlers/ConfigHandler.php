<?php
namespace MythicalClient\Handlers;

class ConfigHandler
{
    /*  
        MythicalClient | ConfigHandler

        This code is the code that manages the config from the config.ini file!

    */

    /**
     * @var string The path to the configuration file.
     */
    private static $configFile = __DIR__ . '/../../config.ini';

    /**
     * Get configuration values.
     *
     * @param string|null $section The section name.
     * @param string|null $key     The key within the section.
     *
     * @return mixed|null The entire configuration, a specific section, or a specific value.
     */
    public static function get($section = null, $key = null)
    {
        $config = parse_ini_file(self::$configFile, true);

        if ($section !== null) {
            if ($key !== null && isset($config[$section][$key])) {
                return $config[$section][$key];
            }

            return $config[$section] ?? null;
        }

        return $config;
    }

    /**
     * Set a new configuration value.
     *
     * @param string $section The section name.
     * @param string $key     The key within the section.
     * @param mixed  $value   The value to set.
     */
    public static function set($section, $key, $value)
    {
        $config = self::get();

        if (!isset($config[$section])) {
            $config[$section] = [];
        }

        $config[$section][$key] = $value;

        self::write($config);
    }

    /**
     * Update an existing configuration value.
     *
     * @param string $section The section name.
     * @param string $key     The key within the section.
     * @param mixed  $value   The new value.
     *
     * @return bool True if the update is successful, false if the key doesn't exist.
     */
    public static function update($section, $key, $value)
    {
        $config = self::get();

        if (isset($config[$section][$key])) {
            $config[$section][$key] = $value;
            self::write($config);
            return true;
        }

        return false;
    }

    /**
     * Write configuration changes to the file.
     *
     * @param array $config The modified configuration array.
     */
    public static function write($config)
    {
        $content = '';

        foreach ($config as $section => $values) {
            $content .= "[$section]\n";
            foreach ($values as $key => $value) {
                $content .= "$key = \"$value\"\n";
            }
            $content .= "\n";
        }

        file_put_contents(self::$configFile, $content);
    }
}
?>