<?php

namespace MythicalClient\Managers;

use MythicalClient\Handlers\DatabaseConnectionHandler;
use MythicalClient\Handlers\EncryptionHandler;
use MythicalClient\Handlers\ConfigHandler;

class SessionManager {
    private $dbConnection;
    public function __construct() {
        $this->dbConnection = DatabaseConnectionHandler::getConnection();
    }
    /**
     * Check if user is logged in
     */
    public function authenticateUser() {
        if(isset($_COOKIE['token'])) {
            $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE['token']);
            $query = "SELECT * FROM users WHERE token='".$session_id."'";
            $result = mysqli_query($this->dbConnection, $query);

            if(mysqli_num_rows($result) > 0) {
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
     * Get the user information from the database
     * 
     * @param string $info The info you want to get
     * 
     * @return string|null The info or null
     */
    public function getUserInfo($info) {
        $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE["token"]);
        $safeInfo = $this->dbConnection->real_escape_string($info);
        $query = "SELECT `$safeInfo` FROM users WHERE token='$session_id' LIMIT 1";
        $result = $this->dbConnection->query($query);

        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$info];
        } else {
            return null; // User or data not found
        }
    }

    /**
     * If user is not logged in it will reddirect them to login! 
     * This code also saves the url where the user is. 
     */
    private function redirectToLogin($fullUrl) {
        $this->deleteCookies();
        header('location: /auth/login?r='.$fullUrl);
        die();
    }
    /**
     * This will delete all user cookies to ensure that he will be 
     * logged out if this account
     */
    private function deleteCookies() {
        if(isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
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
    public function getIP() {
        if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
    /**
     * Get the url where the user is 
     * 
     * @return string|null The url
     */
    private function getFullUrl() {
        $fullUrl = "http";
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
            $fullUrl .= "s";
        }
        $fullUrl .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return $fullUrl;
    }

    /**
     * Update account settings inside the database 
     * 
     * @param string $user_id The user id
     * @param string $first_name The first name
     * @param string $last_name The last name 
     * @param string $email The email
     * @param string $password The password
     * 
     * @return bool The stauts
     */
    public function updateAccount($user_id, $first_name, $last_name, $email, $password) {
        $query = "UPDATE `users` SET";

        $params = array();
        $types = "";

        if($email !== null) {
            $query .= " email = ?,";
            $params[] = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        if($password !== null) {
            $query .= " password = ?,";
            $params[] = $password;
            $types .= "s";
        }

        if($first_name !== null) {
            $query .= " first_name = ?,";
            $params[] = EncryptionHandler::encrypt($first_name, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        if($last_name !== null) {
            $query .= " last_name = ?,";
            $params[] = EncryptionHandler::encrypt($last_name, ConfigHandler::get("app", "key"));
            $types .= "s";
        }

        $query = rtrim($query, ',');

        $query .= " WHERE token = ?";
        $params[] = $user_id;
        $types .= "s";

        $stmt = $this->dbConnection->prepare($query);

        $stmt->bind_param($types, ...$params);

        return $stmt->execute();
    }


    /**
     * Create a user secret key
     * 
     * @param string $username The username of the user
     * @param string $email The email of the user
     *  
     * @return string|null The new key or null if something failed
     */

    public function createKey($username, $email) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $charArrayLength = strlen($chars) - 1;
        $length = 12;
        $key = "";
        for($i = 0; $i < $length; $i++) {
            $key .= $chars[mt_rand(0, $charArrayLength)];
        }

        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);

        $e_username = EncryptionHandler::encrypt($username, ConfigHandler::get("app", "key"));
        $e_email = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
        $encoded_timestamp = EncryptionHandler::encrypt($formatted_timestamp, ConfigHandler::get("app", "key"));

        $userToken = 'MythicalClientAccountKey_'.$e_username.$e_email.$encoded_timestamp.$key;
        return $userToken;
    }

    /**
     * Reset the user account key
     * 
     * @param string $user_id The account token
     * 
     * @return bool
     */
    public function resetKey($user_id) {
        // Generate a new key
        $newKeyGen = $this->createKey("resetmykey", "resetMyKey@infoKey.net");
        $newKey = "mcc_".base64_encode($newKeyGen);

        // Update the user's token in the database
        $query = "UPDATE `users` SET token = ? WHERE token = ?";
        $stmt = $this->dbConnection->prepare($query);

        // Bind parameters
        $stmt->bind_param("ss", $newKey, $user_id);

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
    public function createUser($username, $email, $first_name, $last_name, $password, $avatar, $uid, $token, $ip, $verification_code) {
        $query = "INSERT INTO users (username, email, first_name, last_name, password, avatar, user_id, token, first_ip, last_ip, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbConnection->prepare($query);
        $encryptedUsername = EncryptionHandler::encrypt($username, ConfigHandler::get("app", "key"));
        $encryptedEmail = EncryptionHandler::encrypt($email, ConfigHandler::get("app", "key"));
        $encryptedFirstName = EncryptionHandler::encrypt($first_name, ConfigHandler::get("app", "key"));
        $encryptedLastName = EncryptionHandler::encrypt($last_name, ConfigHandler::get("app", "key"));
        $encryptedIp = EncryptionHandler::encrypt($ip, ConfigHandler::get("app", "key"));
        $encryptedAvatar = EncryptionHandler::encrypt($avatar, ConfigHandler::get("app", "key"));
        $encryptedUID = EncryptionHandler::encrypt($uid, ConfigHandler::get("app", "key"));
        $myToken = "mcc_".base64_encode($token);
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