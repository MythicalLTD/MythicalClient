<?php 
use MythicalClient\Handlers\EncryptionHandler;
if (isset($_GET['encrypt'])) {
    $text = $_GET['text'];
    $key = $_GET['key'];
    $entxt = EncryptionHandler::encrypt($text,$key);
    die($entxt);    
}
else if (isset($_GET['decrypt'])) {
    $text = $_GET['text'];
    $key = $_GET['key'];
    $entxt = EncryptionHandler::decrypt($text,$key);
    die($entxt);    
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MythicalGuard</title>
</head>
<body>
    <form method="get">
        <h1>Encrypt Data</h1>
        <input type="text" name="text">
        <input type="password" name="key">
        <button type="submit" name="encrypt">Go</button>
    </form>
    <form method="get">
        <h1>Decrypt Data</h1>
        <input type="text" name="text">
        <input type="password" name="key">
        <button type="submit" name="decrypt">Go</button>
    </form>
</body>
</html>