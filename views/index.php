<?php 
use MythicalClient\Managers\SessionManager;

$session = new SessionManager();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MythicalClient - Home</title>
</head>
<body>
    <?php
     echo($session->createKey("nayskutzu","test@gmail.com"));?>
    <p>
    </p>
</body>
</html>