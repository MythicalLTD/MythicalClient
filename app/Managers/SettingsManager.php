<?php
namespace MythicalClient\Managers;

use Exception;
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EncryptionHandler;

class SettingsManager
{
    /**
     * Get settings from the database
     * @param string $settingsName The name of the setting from the table!
     * 
     * @return string|null The value form the table or null for not found!
     */
    public static function get($settingName)
    {
        try {
            $conn = DatabaseConnectionHandler::getConnection();
            $safeSettingName = $conn->real_escape_string($settingName);

            $query = "SELECT `$safeSettingName` FROM settings LIMIT 1";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $conn->close();
                return EncryptionHandler::decrypt($row[$settingName],ConfigHandler::get("app","key"));
            } else {
                $conn->close();
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
    /**
     * Update the database with the new setting value
     * @param string $settingName Name for the value to update
     * @param string $settingvalue Value to update
     * 
     * @return bool If the update failed or not
     */
    public static function set($settingName, $settingValue)
    {
        try {
            $conn = DatabaseConnectionHandler::getConnection();
            $safeSettingName = $conn->real_escape_string($settingName);
            $safeSettingValue = $conn->real_escape_string($settingValue);

            $query = "UPDATE settings SET `$safeSettingName` = '".EncryptionHandler::encrypt($safeSettingValue,ConfigHandler::get("app","key"))."'";

            if ($conn->query($query)) {
                $conn->close();
                return true;
            } else {
                $conn->close();
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}

?>