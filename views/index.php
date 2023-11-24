<?php
use MythicalClient\Managers\SessionManager;
use MythicalClient\Managers\SnowflakeManager;

$id = SnowflakeManager::getUniqueUserID();


$session = new SessionManager();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MythicalClient - Home</title>
</head>

<body>
    <p>
        <?php
        echo $id;
        ?>
    </p>
</body>

</html>