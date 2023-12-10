<?php

namespace MythicalClient\Managers;

use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EncryptionHandler;
use MythicalClient\Handlers\ConfigHandler;

class SessionManager
{
    private $dbConnection;
    public function __construct()
    {
        $this->dbConnection = DatabaseConnectionHandler::getConnection();
    }
    /**
     * Check if user is logged in
     */
    public function authenticateUser(): void
    {
        if (isset($_COOKIE['token'])) {
            $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE['token']);
            $query = "SELECT * FROM users WHERE token='" . $session_id . "'";
            $result = mysqli_query($this->dbConnection, $query);

            if (mysqli_num_rows($result) > 0) {
                $_SESSION["token"] = $session_id;
                $_SESSION['loggedin'] = true;
            } else {
                $this->redirectToLogin($this->getFullUrl());
            }
        } else {
            $this->redirectToLogin($this->getFullUrl());
        }
    }

    /**
     * Get the user info
     * 
     * @param string $info The info you want to get
     * @param bool $encrypted If the info that you want to get is encrypted or not
     * 
     * @return string|null The info or null
     */
    public function getUserInfo(string $info, bool $encrypted): string|null
    {
        $this->authenticateUser();
        $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE["token"]);
        $safeInfo = $this->dbConnection->real_escape_string($info);
        $query = "SELECT `$safeInfo` FROM users WHERE token='$session_id' LIMIT 1";
        $result = $this->dbConnection->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($encrypted == false) {
                return $row[$info];
            } else {
                return EncryptionHandler::decrypt($row[$info], ConfigHandler::get("app", "key"));
            }
        } else {
            return null;
        }
    }

    /**
     * Get user info using the user id
     * 
     * @param string $user_id The user id (NOT Encrypted)
     * @param string $info The info you want to get
     * @param bool $encrypted If the info that you want to get is encrypted or not
     * 
     * @return string|null The info or null
     */
    public function getUserInfoID(string $user_id, string $info, bool $encrypted): string|null
    {
        //Check if the user exists first!
        if (!$this->doesUserExist($user_id)) {
            return null;
        }

        $session_id = EncryptionHandler::encrypt(mysqli_real_escape_string($this->dbConnection, $user_id), ConfigHandler::get("app", "key"));
        $safeInfo = $this->dbConnection->real_escape_string($info);
        $query = "SELECT `$safeInfo` FROM users WHERE user_id='$session_id' LIMIT 1";
        $result = $this->dbConnection->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($encrypted == false) {
                return $row[$info];
            } else {
                return EncryptionHandler::decrypt($row[$info], ConfigHandler::get("app", "key"));
            }
        } else {
            return null;
        }
    }

    /**
     * If user is not logged in it will reddirect them to login! 
     * This code also saves the url where the user is. 
     */
    private function redirectToLogin(string $fullUrl): void
    {
        $this->deleteCookies();
        header('location: /auth/login?r=' . $fullUrl);
        die();
    }
    /**
     * This will delete all user cookies to ensure that he will be 
     * logged out if this account
     */
    private function deleteCookies(): void
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }

    /**
     * Get the ip of the user
     * 
     * @return string|null The ip of the user
     */
    public function getIP(): string|null
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
    /**
     * Get the url where the user is 
     * 
     * @return string The url
     */
    private function getFullUrl(): string
    {
        $fullUrl = "http";
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
            $fullUrl .= "s";
        }
        $fullUrl .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $fullUrl;
    }

    /**
     * Update a row from the user table using a id
     * 
     * @param string $user_id The user id (Encrypted)
     * @param string $row The row you want to update
     * @param string $value The value that you want to put inside the row!
     * @param bool $encrypt If the value shall be encrypted! 
     * 
     * @return bool True if the update was successful, false otherwise
     */
    public function updateRowID(string $user_id, string $row, string $value, bool $encrypt): bool
    {
        $safe_row = mysqli_real_escape_string($this->dbConnection, $row);
        if ($encrypt == true) {
            $safe_value = EncryptionHandler::encrypt(mysqli_real_escape_string($this->dbConnection, $value), ConfigHandler::get("app", "key"));
        } else {
            $safe_value = mysqli_real_escape_string($this->dbConnection, $value);
        }
        $safe_token = mysqli_real_escape_string($this->dbConnection, $user_id);

        $query = "UPDATE `users` SET `$safe_row` = ? WHERE `user_id` = ?";

        $stmt = mysqli_prepare($this->dbConnection, $query);

        mysqli_stmt_bind_param($stmt, 'ss', $safe_value, $safe_token);

        return $stmt->execute();
    }


    /**
     * Update a row from the user table
     * 
     * @param string $row The row you want to update
     * @param string $value The value that you want to put inside the row!
     * @param bool $encrypt If the value shall be encrypted! 
     * 
     * @return bool True if the update was successful, false otherwise
     */
    public function updateRow(string $row, string $value, bool $encrypt): bool
    {
        $this->authenticateUser();
        $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE["token"]);

        $safe_row = mysqli_real_escape_string($this->dbConnection, $row);
        if ($encrypt == true) {
            $safe_value = EncryptionHandler::encrypt(mysqli_real_escape_string($this->dbConnection, $value), ConfigHandler::get("app", "key"));
        } else {
            $safe_value = mysqli_real_escape_string($this->dbConnection, $value);
        }
        $safe_token = mysqli_real_escape_string($this->dbConnection, $session_id);

        $query = "UPDATE `users` SET `$safe_row` = ? WHERE `token` = ?";

        $stmt = mysqli_prepare($this->dbConnection, $query);

        mysqli_stmt_bind_param($stmt, 'ss', $safe_value, $safe_token);

        return $stmt->execute();
    }

    /**
     * Update account settings inside the database 
     * 
     * @param string $first_name The first name
     * @param string $last_name The last name 
     * @param string $email The email
     * @param string $password The password
     * 
     * @return bool The stauts
     */
    public function updateAccount(string $first_name, string $last_name, string|null $email, string|null $password): bool
    {
        $this->authenticateUser();
        $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE["token"]);

        $query = "UPDATE `users` SET";

        $params = array();
        $types = "";

        if ($email !== null) {
            $query .= " email = ?,";
            $params[] = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        if ($password !== null) {
            $query .= " password = ?,";
            $params[] = $password;
            $types .= "s";
        }

        if ($first_name !== null) {
            $query .= " first_name = ?,";
            $params[] = EncryptionHandler::encrypt($first_name, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        if ($last_name !== null) {
            $query .= " last_name = ?,";
            $params[] = EncryptionHandler::encrypt($last_name, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        $query = rtrim($query, ',');

        $query .= " WHERE token = ?";
        $params[] = mysqli_real_escape_string($this->dbConnection, $session_id);
        ;
        $types .= "s";

        $stmt = $this->dbConnection->prepare($query);

        $stmt->bind_param($types, ...$params);

        return $stmt->execute();
    }



    /**
     * Check if a user exists in the database based on user ID
     * 
     * @param string $uid The user ID to check (NOT Encrypted)
     * 
     * @return bool True if the user exists, false otherwise
     */
    public function doesUserExist($uid): bool
    {
        $e_uid = EncryptionHandler::encrypt($uid, ConfigHandler::get("app", "key"));

        $query = "SELECT COUNT(*) AS count FROM users WHERE user_id = ?";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bind_param("s", $e_uid);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];

        $stmt->close();

        return $count > 0;
    }

    /**
     * Create a user secret key
     * 
     * @param string $username The username of the user
     * @param string $email The email of the user
     *  
     * @return string|null The new key or null if something failed
     */

    public function createKey($username, $email): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $charArrayLength = strlen($chars) - 1;
        $length = 12;
        $key = "";
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[mt_rand(0, $charArrayLength)];
        }

        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);

        $e_username = EncryptionHandler::encrypt($username, ConfigHandler::get("app", "key"));
        $e_email = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
        $encoded_timestamp = EncryptionHandler::encrypt($formatted_timestamp, ConfigHandler::get("app", "key"));

        $userToken = 'MythicalClientAccountKey_' . $e_username . $e_email . $encoded_timestamp . $key;
        return $userToken;
    }

    /**
     * Reset the user account key
     * 
     * @param string $user_token The account token
     * 
     * @return bool The status
     */
    public function resetKey($user_token): bool
    {
        // Generate a new key
        $newKeyGen = $this->createKey("resetmykey", "resetMyKey@infoKey.net");
        $newKey = "mcc_" . base64_encode($newKeyGen);

        // Update the user's token in the database
        $query = "UPDATE `users` SET token = ? WHERE token = ?";
        $stmt = $this->dbConnection->prepare($query);

        // Bind parameters
        $stmt->bind_param("ss", $newKey, $user_token);

        // Execute the query
        $success = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $success;
    }

    /**
     * Create a user inside the database
     * 
     * @param string $username The username
     * @param string $email The email
     * @param string $first_name The first name
     * @param string $last_name The last name
     * @param string $password The password
     * @param string $avatar The avatar
     * @param string $uid The user id
     * @param string $token The user token
     * @param string $ip The ip of the user
     * @param string $verification_code The email verification code
     * 
     * @return bool If this fails or not
     */
    public function createUser(string $username, string $email, string $first_name, string $last_name, string $password, string $avatar, string $uid, string $token, string $ip, string $verification_code): bool
    {
        $query = "INSERT INTO users (username, email, first_name, last_name, password, avatar, user_id, token, first_ip, last_ip, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbConnection->prepare($query);
        $encryptedUsername = EncryptionHandler::encrypt($username, ConfigHandler::get("app", "key"));
        $encryptedEmail = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
        $encryptedFirstName = EncryptionHandler::encrypt($first_name, ConfigHandler::get("app", "key"));
        $encryptedLastName = EncryptionHandler::encrypt($last_name, ConfigHandler::get("app", "key"));
        $encryptedIp = EncryptionHandler::encrypt($ip, ConfigHandler::get("app", "key"));
        $encryptedAvatar = EncryptionHandler::encrypt($avatar, ConfigHandler::get("app", "key"));
        $encryptedUID = EncryptionHandler::encrypt($uid, ConfigHandler::get("app", "key"));
        $myToken = "mcc_" . base64_encode($token);
        $stmt->bind_param(
            "sssssssssss",
            $encryptedUsername,
            $encryptedEmail,
            $encryptedFirstName,
            $encryptedLastName,
            $password,
            $encryptedAvatar,
            $encryptedUID,
            $myToken,
            $encryptedIp,
            $encryptedIp,
            $verification_code
        );
        return $stmt->execute();
    }
}
?>