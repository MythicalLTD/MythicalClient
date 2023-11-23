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
     public function authenticateUser()
     {
         if (isset($_COOKIE['token'])) {
             $session_id = mysqli_real_escape_string($this->dbConnection,$_COOKIE['token']);
             $query = "SELECT * FROM mythicaldash_users WHERE api_key='" . $session_id . "'";
             $result = mysqli_query($this->dbConnection, $query);
 
             if (mysqli_num_rows($result) > 0) {
                 session_start();
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
     public function getUserInfo($info)
     {
         $session_id = mysqli_real_escape_string($this->dbConnection, $_COOKIE["token"]);
         $safeInfo = $this->dbConnection->real_escape_string($info);
         $query = "SELECT `$safeInfo` FROM mythicaldash_users WHERE api_key='$session_id' LIMIT 1";
         $result = $this->dbConnection->query($query);
 
         if ($result && $result->num_rows > 0) {
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
     private function redirectToLogin($fullUrl)
     {
         $this->deleteCookies();
         header('location: /auth/login?r=' . $fullUrl);
         die();
     }
    /**
     * This will delete all user cookies to ensure that he will be 
     * logged out if this account
     */
     private function deleteCookies()
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
     public function getIP()
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
     * @return string|null The url
     */
     private function getFullUrl()
     {
         $fullUrl = "http";
         if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
             $fullUrl .= "s";
         }
         $fullUrl .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
         return $fullUrl;
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
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[mt_rand(0, $charArrayLength)];
        }

        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);

        $e_username = EncryptionHandler::encrypt($username, ConfigHandler::get("app","key"));
        $e_email = EncryptionHandler::encrypt($username, ConfigHandler::get("app","key"));
        $encoded_timestamp = EncryptionHandler::encrypt($username, ConfigHandler::get("app","key"));
        
        $userToken = 'MythicalClientUK_'.ConfigHandler::get("app","name").'_'.$e_username.$e_email.$encoded_timestamp.$key;
        return $userToken;
     }
}
?>