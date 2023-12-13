<?php
namespace MythicalClient\Handlers;

use mysqli;
use MythicalClient\Handlers\ConfigHandler;
use MythicalClient\App;

class DatabaseHandler
{
    private static $connection;

    /**
     * This just gets the database connection!
     */
    public static function getConnection(): mysqli
    {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli(
                ConfigHandler::get("database", "host"),
                ConfigHandler::get("database", "username"),
                ConfigHandler::get("database", "password"),
                ConfigHandler::get("database", "name"),
                ConfigHandler::get("database", "port")
            );

            if (self::$connection->connect_error) {
                App::Crash("Failed to connect to database: " . self::$connection->connect_error);
                die();
            }
        }

        return self::$connection;
    }

    /**
     * Close the database connection
     */
    public static function closeConnection()
    {
        if (isset(self::$connection)) {
            self::$connection->close();
            self::$connection = null;
        }
    }

    /**
     * Execute a database query
     * 
     * @param string $query The SQL query
     * @param array $params An array of parameters to bind to the query
     * @param bool $returnResult Whether to return the result set for select queries
     * 
     * @return mixed True if the query was successful (for non-select queries), mysqli_result for select queries, false on failure
     */
    private static function executeQuery(string $query, array $params = [], bool $returnResult = false)
    {
        $connection = self::getConnection();
        $stmt = mysqli_prepare($connection, $query);

        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
        }

        $result = $stmt->execute();

        if ($returnResult) {
            $resultSet = $stmt->get_result();
            $stmt->close();
            return $resultSet;
        }

        $stmt->close();

        // Close the connection after use
        self::closeConnection();

        return $result;
    }

    /**
     * Update a value inside a row 
     * 
     * @param string $table The database table
     * @param string $row The database row
     * @param string $id The entry id  
     * @param string $value The value that you want to set
     * @param bool $encrypted Shall the value be encrypted before updating the database
     * 
     * @return bool True if the update was successful, false otherwise
     */
    public static function update(string $table, string $row, string $id, string $value, bool $encrypted): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);
        $safe_row = mysqli_real_escape_string($connection, $row);
        $safe_id = mysqli_real_escape_string($connection, $id);

        if ($encrypted) {
            $value = EncryptionHandler::encrypt(mysqli_real_escape_string($connection, $value), ConfigHandler::get("app", "key"));
        } else {
            $value = mysqli_real_escape_string($connection, $value);
        }

        $query = "UPDATE `$safe_table` SET `$safe_row` = ? WHERE `id` = ?";
        $params = [$value, $safe_id];

        return self::executeQuery($query, $params);
    }

    /**
     * Delete a whole row from the database
     * 
     * @param string $table The database table
     * @param string $id The entry id
     * 
     * @return bool True if the delete was successful, false otherwise
     */
    public static function delete(string $table, string $id): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);
        $safe_id = mysqli_real_escape_string($connection, $id);

        $query = "DELETE FROM `$safe_table` WHERE `$safe_table`.`id` = ?";
        $params = [$safe_id];

        return self::executeQuery($query, $params);
    }

    /**
     * Add a new record to the database
     * 
     * @param string $table The database table
     * @param array $data An associative array of column names and values to insert
     * 
     * @return bool True if the insert was successful, false otherwise
     */
    public static function add(string $table, array $data): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);
        $columns = implode('`,`', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));

        $query = "INSERT INTO `$safe_table` (`$columns`) VALUES ($values)";
        $params = array_values($data);

        return self::executeQuery($query, $params);
    }

    /**
     * Select data from the database
     * 
     * @param string $table The database table
     * @param array $columns An array of column names to select
     * @param array $conditions An associative array of conditions (column => value)
     * @param bool $encrypted If we shall decrypt this before we return
     * 
     * @return array|false An array of rows if the select was successful, false otherwise
     */
    public static function select(string $table, array $columns = ['*'], array $conditions = [], bool $encrypted) : array|false 
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);
        $safe_columns = implode('`,`', $columns);
        $conditionsStr = '';

        if (!empty($conditions)) {
            $conditionsStr = 'WHERE ';
            $conditionArr = [];

            foreach ($conditions as $column => $value) {
                $safe_column = mysqli_real_escape_string($connection, $column);
                $conditionArr[] = "`$safe_column` = ?";
            }

            $conditionsStr .= implode(' AND ', $conditionArr);
        }

        $query = "SELECT `$safe_columns` FROM `$safe_table` $conditionsStr";
        $params = array_values($conditions);

        $result = self::executeQuery($query, $params, true);

        if ($result !== false) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            if ($encrypted == TRUE) {
                $data = EncryptionHandler::decrypt($rows,ConfigHandler::get("app","key"));
                return $data;
            } else {
                return $rows;
            }
        }

        return false;
    }

     /**
     * Create a table in the database
     * 
     * @param string $table The name of the table to create
     * @param array $columns An associative array of column definitions (column name => data type)
     * 
     * @return bool True if the table creation was successful, false otherwise
     */
    public static function createTable(string $table, array $columns): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);
        $columnDefinitions = [];

        foreach ($columns as $columnName => $columnType) {
            $safe_column = mysqli_real_escape_string($connection, $columnName);
            $columnDefinitions[] = "`$safe_column` $columnType";
        }

        $columnsStr = implode(',', $columnDefinitions);

        $query = "CREATE TABLE IF NOT EXISTS `$safe_table` ($columnsStr)";

        return self::executeQuery($query);
    }

    /**
     * Delete a table from the database
     * 
     * @param string $table The name of the table to delete
     * 
     * @return bool True if the table deletion was successful, false otherwise
     */
    public static function deleteTable(string $table): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);

        $query = "DROP TABLE IF EXISTS `$safe_table`";

        return self::executeQuery($query);
    }

    /**
     * Truncate a table in the database
     * 
     * @param string $table The name of the table to truncate
     * 
     * @return bool True if the table truncation was successful, false otherwise
     */
    public static function truncateTable(string $table): bool
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);

        $query = "TRUNCATE TABLE `$safe_table`";

        return self::executeQuery($query);
    }

    /**
     * Get information about the columns in a table
     * 
     * @param string $table The name of the table
     * 
     * @return array|false An array of column information if successful, false otherwise
     */
    public static function getTableColumns(string $table)
    {
        $connection = self::getConnection();

        $safe_table = mysqli_real_escape_string($connection, $table);

        $query = "DESCRIBE `$safe_table`";

        $result = self::executeQuery($query, [], true);

        if ($result !== false) {
            $columns = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            return $columns;
        }

        return false;
    } 
}
?>
