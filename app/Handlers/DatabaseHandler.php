<?php
namespace MythicalClient\Handlers;

use mysqli;
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\App;

class DatabaseHandler
{
    /**
     * This just gets the database connection!
     */
    public static function getConnection() : mysqli
    {
        $conn = new mysqli(
            ConfigHandler::get("database", "host"),
            ConfigHandler::get("database", "username"),
            ConfigHandler::get("database", "password"),
            ConfigHandler::get("database", "name"),
            ConfigHandler::get("database", "port")
        );

        if ($conn->connect_error) {
            App::Crash("Failed to connect to database: " . $conn->connect_error);
            die();
        }

        return $conn;
    }
}
?>