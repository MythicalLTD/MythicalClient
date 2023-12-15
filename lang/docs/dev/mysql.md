# DatabaseHandler Documentation

The `DatabaseHandler` class is designed to provide a convenient interface for a safe method of interacting with a MySQL database using PHP. This documentation provides an overview of the class methods and how to use them.

## Getting Started

1. **Namespace:** `MythicalClient\Handlers`
2. **Class Name:** `DatabaseHandler`


## Methods

### 1. `getConnection()`

- **Description:** Retrieves the database connection.
- **Return Type:** `mysqli`

### 2. `closeConnection()`

- **Description:** Closes the database connection.

### 3. `update(string $table, string $row, string $id, string $value, bool $encrypted): bool`

- **Description:** Updates a value inside a row in the database.
- **Parameters:**
  - `$table`: Database table name.
  - `$row`: Database row name.
  - `$id`: Entry ID.
  - `$value`: Value to set.
  - `$encrypted`: Whether the value should be encrypted.
- **Return Type:** `bool`

### 4. `delete(string $table, string $id): bool`

- **Description:** Deletes a whole row from the database.
- **Parameters:**
  - `$table`: Database table name.
  - `$id`: Entry ID.
- **Return Type:** `bool`

### 5. `add(string $table, array $data): bool`

- **Description:** Adds a new record to the database.
- **Parameters:**
  - `$table`: Database table name.
  - `$data`: Associative array of column names and values.
- **Return Type:** `bool`

### 6. `select(string $table, array $columns = ['*'], array $conditions = [], bool $encrypted): array|false`

- **Description:** Selects data from the database.
- **Parameters:**
  - `$table`: Database table name.
  - `$columns`: Array of column names to select (default is all columns).
  - `$conditions`: Associative array of conditions (column => value).
  - `$encrypted`: Whether to decrypt the result.
- **Return Type:** `array|false`

### 7. `createTable(string $table, array $columns): bool`

- **Description:** Creates a table in the database.
- **Parameters:**
  - `$table`: Table name.
  - `$columns`: Associative array of column definitions (column name => data type).
- **Return Type:** `bool`

### 8. `deleteTable(string $table): bool`

- **Description:** Deletes a table from the database.
- **Parameters:**
  - `$table`: Table name.
- **Return Type:** `bool`

### 9. `truncateTable(string $table): bool`

- **Description:** Truncates a table in the database.
- **Parameters:**
  - `$table`: Table name.
- **Return Type:** `bool`

### 10. `getTableColumns(string $table): array|false`

- **Description:** Gets information about the columns in a table.
- **Parameters:**
  - `$table`: Table name.
- **Return Type:** `array|false`

## Examples

1. `getConnection()`

```php
// Get the database connection
$connection = DatabaseHandler::getConnection();
```

2. `closeConnection()`

```php
// Close the database connection
DatabaseHandler::closeConnection();
```

3. `update()`

```php
// Update a value in the 'users' table, setting the 'email' for the user with ID '123'
$result = DatabaseHandler::update('users', 'email', '123', 'newemail@example.com', true);
```

4. `delete()`

```php
// Delete a user with ID '456' from the 'users' table
$result = DatabaseHandler::delete('users', '456');
```

5. `add()` 
```php
// Add a new user to the 'users' table
$userData = ['username' => 'john_doe', 'email' => 'john@example.com', 'password' => 'hashed_password'];
$result = DatabaseHandler::add('users', $userData);
```

6. `select()`
```php
// Select all users with the role 'admin' from the 'users' table
$adminUsers = DatabaseHandler::select('users', ['id', 'username'], ['role' => 'admin'], false);
```

7. `createTable()`
```php
// Create a 'products' table with columns 'id', 'name', and 'price'
$columns = ['id' => 'INT', 'name' => 'VARCHAR(255)', 'price' => 'DECIMAL(10,2)'];
$result = DatabaseHandler::createTable('products', $columns);
```

8. `deleteTable()`
```php
// Delete the 'old_table' table
$result = DatabaseHandler::deleteTable('old_table');
```

9. `truncateTable()` 
```php
// Truncate the 'log_entries' table
$result = DatabaseHandler::truncateTable('log_entries');
```

10. `getTableColumns()` 
```php
// Get information about columns in the 'users' table
$userColumns = DatabaseHandler::getTableColumns('users');
```