using MySqlConnector;

namespace MythicalClient
{
    public class DatabaseHelper
    {
        public static string? connectionString;

        public static void getConnection()
        {
            if (ConfigManager.doesConfigExist() == true)
            {
                string? dbHost = ConfigManager.GetSetting("database", "host");
                string? dbPort = ConfigManager.GetSetting("database", "port");
                string? dbUsername = ConfigManager.GetSetting("database", "username");
                string? dbPassword = ConfigManager.GetSetting("database", "password");
                string? dbName = ConfigManager.GetSetting("database", "name");
                connectionString = $"Server={dbHost};Port={dbPort};User ID={dbUsername};Password={dbPassword};Database={dbName}";
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like your main config file is missing!");
            }
        }
        public static void CheckConnection()
        {
            if (ConfigManager.doesConfigExist() == true)
            {
                string? host = ConfigManager.GetSetting("database", "host");
                string? port = ConfigManager.GetSetting("database", "port");
                string? username = ConfigManager.GetSetting("database", "username");
                string? password = ConfigManager.GetSetting("database", "password");
                string? dbName = ConfigManager.GetSetting("database", "name");
                using var connection = new MySqlConnection($"Server={host};Port={port};User ID={username};Password={password};Database={dbName}");
                connection.Open();
                Program.logger.Log(LogType.Info, "Connection to MySQL: OK!");
                connection.Close();
                Program.Stop();

            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like your main config file is missing!");
                Program.Stop();
            }
        }
        public static void ExecuteSQLScript(MySqlConnection connection, string cmd)
        {
            using (var command = new MySqlCommand(cmd, connection))
            {
                command.ExecuteNonQuery();
            }

        }
        public static void Rebuild()
        {
            if (ConfigManager.doesConfigExist() == true)
            {
                
                if (File.Exists("/var/www/mythicalclient/caches/user_activity.json")) {
                    File.Delete("/var/www/mythicalclient/caches/user_activity.json");
                }
                if (File.Exists("/var/www/mythicalclient/caches/user_usedids.json")) {
                    File.Delete("/var/www/mythicalclient/caches/user_usedids.json");
                }
                if (File.Exists("/var/www/mythicalclient/migrates.ini")) {
                    File.Delete("/var/www/mythicalclient/migrates.ini");
                }
                MigrationHelper x = new MigrationHelper();
                x.Now();
                Program.Stop();
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like your main config file is missing!");
                Program.Stop();
            }
        }
        public static void StartConfigDB()
        {
            if (ConfigManager.doesConfigExist() == true)
            {
                string defaultHost = "127.0.0.1";
                string defaultPort = "3306";
                string defaultUsername = "mythicalclient";
                string defaultdbName = "mythicalclient";

                Program.logger.Log(LogType.Info, "Hi, please fill in your database configuration for MythicalClient.");
                Console.Write("Host [");
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.Write($"{defaultHost}");
                Console.ResetColor();
                Console.Write("]: ");

                string? host = Console.ReadLine();
                if (string.IsNullOrWhiteSpace(host))
                {
                    host = defaultHost;
                }

                Console.Write("Port [");
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.Write($"{defaultPort}");
                Console.ResetColor();
                Console.Write("]: ");

                string? port = Console.ReadLine();
                if (string.IsNullOrWhiteSpace(port))
                {
                    port = defaultPort;
                }

                Console.Write("Username [");
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.Write($"{defaultUsername}");
                Console.ResetColor();
                Console.Write("]: ");

                string? username = Console.ReadLine();
                if (string.IsNullOrWhiteSpace(username))
                {
                    username = defaultUsername;
                }

                Console.Write("Password: ");
                string password = ReadPasswordInput();

                Console.Write("Database Name [");
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.Write($"{defaultdbName}");
                Console.ResetColor();
                Console.Write("]: ");

                string? dbName = Console.ReadLine();
                if (string.IsNullOrWhiteSpace(dbName))
                {
                    dbName = defaultdbName;
                }
                if (string.IsNullOrEmpty(host) || string.IsNullOrEmpty(port) || string.IsNullOrEmpty(username) || string.IsNullOrEmpty(password) || string.IsNullOrEmpty(dbName))
                {
                    Program.logger.Log(LogType.Error, "Invalid input. Please provide all the required values.");
                    Program.Stop();
                }
                try
                {
                    using var connection = new MySqlConnection($"Server={host};Port={port};User ID={username};Password={password};Database={dbName}");
                    connection.Open();
                    Program.logger.Log(LogType.Info, "Connected to MySQL, saving database configuration to config.");
                    connection.Close();
                    ConfigManager.UpdateSetting("database", "host", host);
                    ConfigManager.UpdateSetting("database", "port", port);
                    ConfigManager.UpdateSetting("database", "username", username);
                    ConfigManager.UpdateSetting("database", "password", password);
                    ConfigManager.UpdateSetting("database", "name", dbName);

                    Program.logger.Log(LogType.Info, "Done we saved your MySQL connection to your config file");
                    Program.Stop();
                }
                catch (Exception ex)
                {
                    Program.logger.Log(LogType.Error, $"Failed to connect to MySQL: {ex.Message}");
                    Program.Stop();
                }
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like your main config file is missing!");
                Program.Stop();
            }

        }
        private static string ReadPasswordInput()
        {
            string password = "";
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey(true);
                if (key.Key == ConsoleKey.Enter)
                {
                    Console.WriteLine();
                    break;
                }
                else if (key.Key == ConsoleKey.Backspace)
                {
                    if (password.Length > 0)
                    {
                        password = password.Remove(password.Length - 1);
                        Console.Write("\b \b");
                    }
                }
                else
                {
                    password += key.KeyChar;
                    Console.Write("*");
                }
            }
            return password;
        }
    }
}