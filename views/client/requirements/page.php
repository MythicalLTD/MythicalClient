<?php
// Use MythicalClient namespace and its classes
use MythicalClient\Managers\SessionManager;
use MythicalClient\Managers\ActivityManager;
use MythicalClient\App;

// Initialize language variable with current language
$lang = App::getLang();

// Create a new SessionManager instance
$session = new SessionManager();

// Authenticate the user with the authenticateUser method of SessionManager
$session->authenticateUser();

// Create a new Activity instance
$ActivityManager = new ActivityManager();

?>