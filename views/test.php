<?php
use MythicalClient\Managers\ServerModuleManager;


$moduleManager = new ServerModuleManager();

// Handle enabling or disabling modules based on the request
if (isset($_POST['action']) && isset($_POST['moduleName'])) {
    $moduleName = $_POST['moduleName'];

    switch ($_POST['action']) {
        case 'enable':
            $moduleManager->enableModule($moduleName);
            break;
        case 'disable':
            $moduleManager->disableModule($moduleName);
            break;
    }

    // Redirect to avoid form resubmission on page reload
    header('Location: /test');
    exit();
} else if (isset($_GET['pterodactyl'])) {
    $moduleName = "Pterodactyl";
    $module = new $moduleName;

    $result = $module->CreateUser('user@example.com', 'myUsername', 'myPassword');
    echo $result;
}

// Display information about all modules in an HTML table
$modulesInfo = $moduleManager->getAllModulesInfo();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Status</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .enabled-button,
        .disabled-button {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 3px;
        }

        .enabled-button {
            background-color: green;
            color: white;
        }

        .disabled-button {
            background-color: red;
            color: white;
        }

        .module-icon {
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <h2>Module Status</h2>

    <table>
        <thead>
            <tr>
                <th>Module Name</th>
                <th>Author</th>
                <th>Version</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modulesInfo as $moduleInfo): ?>
                <tr>
                    <td>
                        <img src="<?php echo $moduleInfo['icon']; ?>" width="24" height="24"
                            alt="<?php echo $moduleInfo['moduleName']; ?> Icon" class="module-icon">
                        <?php echo $moduleInfo['moduleName']; ?>
                    </td>
                    <td>
                        <?php echo $moduleInfo['author']; ?>
                    </td>
                    <td>
                        <?php echo $moduleInfo['version']; ?>
                    </td>
                    <td>
                        <?php echo $moduleInfo['description']; ?>
                    </td>
                    <td>
                        <?php
                        if ($moduleInfo['enabled']) {
                            echo '<span class="enabled-button">Enabled</span>';
                        } else {
                            echo '<span class="disabled-button">Disabled</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($moduleInfo['enabled']) {
                            echo '<form method="post" action=""><input type="hidden" name="action" value="disable">';
                            echo '<input type="hidden" name="moduleName" value="' . $moduleInfo['moduleName'] . '">';
                            echo '<a href="/test?pterodactyl=user">Create user</a>';
                            echo '<button type="submit" class="disabled-button">Disable</button></form>';
                        } else {
                            echo '<form method="post" action=""><input type="hidden" name="action" value="enable">';
                            echo '<input type="hidden" name="moduleName" value="' . $moduleInfo['moduleName'] . '">';
                            echo '<button type="submit" class="enabled-button">Enable</button></form>';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>