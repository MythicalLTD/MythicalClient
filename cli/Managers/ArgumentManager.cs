

namespace MythicalClient
{
    public class ArgumentManager
    {
        public static bool ProcessArguments(string[] args)
        {
            if (args.Length == 0)
            {
                return false;
            }

            string option = args[0].ToLower();

            switch (option)
            {
                case "-migrate":
                    try
                    {
                        MigrationHelper mg = new MigrationHelper();
                        mg.Now();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to migrate: " + ex.Message);
                    }
                    break;
                case "-database:setup":
                    try
                    {
                        DatabaseHelper.StartConfigDB();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed: " + ex.Message);
                    }
                    break;
                case "-database:check":
                    try
                    {
                        DatabaseHelper.CheckConnection();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed: " + ex.Message);
                    }
                    break;
                case "-database:rebuild":
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "THIS COMMAND WILL WIPE YOUR DATABASE AND REBUILD IT!");
                    Program.logger.Log(LogType.Warning, "ANY DATA SAVED ON THE DATABASE WILL BE GONE FOREVER!");
                    Program.logger.Log(LogType.Warning, "MAKE SURE YOU KNOW WHAT YOU ARE DOING!!");
                    Program.logger.Log(LogType.Warning, "DO THIS ONLY IF YOU DO NOT USE ANY SERVER MODULE!");
                    Program.logger.Log(LogType.Warning, "ANY LEFT PRODUCTS WILL GET ONLY DELETED INSIDE THE DATABASE!");
                    Program.logger.Log(LogType.Warning, "MAKE SURE YOU DELETE EVERY PRODUCT BEFORE YOU RUN THIS!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "WARNING WARNING WARNING!");
                    Program.logger.Log(LogType.Warning, "Are you sure you want to proceed? (yes/no)");
#pragma warning disable CS8602
                    string? DBuserResponse = Console.ReadLine().Trim().ToLower();
#pragma warning restore CS8602
                    if (DBuserResponse == "yes")
                    {
                        try
                        {
                            DatabaseHelper.Rebuild();

                            return true;
                        }
                        catch (Exception ex)
                        {
                            Program.logger.Log(LogType.Error, $"Failed to generate a key: {ex.Message}");

                        }
                    }
                    else if (DBuserResponse == "no")
                    {
                        Program.logger.Log(LogType.Info, "Action cancelled.");
                        Environment.Exit(0x0);
                    }
                    else
                    {
                        Program.logger.Log(LogType.Info, "Invalid response. Please enter 'yes' or 'no'.");
                        Environment.Exit(0x0);
                    }

                    break;

                case "-help":
                    try
                    {
                        Console.WriteLine("╔≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ MythicalClient CLI ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╗");
                        Console.WriteLine("‖                                                                                   ‖");
                        Console.WriteLine("‖    -help                      ⊳ Opens a help menu with the available commands.    ‖");
                        Console.WriteLine("‖    -version                   ⊳ See the version / build version of the CLI.       ‖");
                        Console.WriteLine("‖    -environment:setup         ⊳ Do a quick setup for the client.                  ‖");
                        Console.WriteLine("‖    -environment:maintenance   ⊳ Toggle maintenance mod on MythicalClient.         ‖");
                        Console.WriteLine("‖    -environment:debug         ⊳ Toggle debug mod on MythicalClient.               ‖");
                        Console.WriteLine("‖    -environment:key           ⊳ Generate a new encryption key for MythicalClient. ‖");
                        Console.WriteLine("‖    -database:check            ⊳ Check the connection to the MySQL server.         ‖");
                        Console.WriteLine("‖    -database:setup            ⊳ Do a quick setup for the database.                ‖");
                        Console.WriteLine("‖    -database:rebuild          ⊳ This will wipe your database and rebuild it.      ‖");
                        Console.WriteLine("‖    -migrate                   ⊳ Create and setup all tables in the database       ‖");
                        Console.WriteLine("‖    -logs:clear                ⊳ Delete all the cached logs for MythicalClient.    ‖");
                        Console.WriteLine("‖                                                                                   ‖");
                        Console.WriteLine("╚≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ Copyright 2023 MythicalSystems ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╝");
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to display help message: " + ex.Message);
                    }
                    break;
                case "-logs:clear":
                    try
                    {
                        Program.logger.PurgeLogs();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to purge the logs: " + ex.Message);

                    }
                    break;
                case "-environment:key":
                    Program.logger.Log(LogType.Warning, "Wow, buddy, this command shall be run only once, and that's when you set up the client. Please do not run this command if you don't know what it does!");
                    Program.logger.Log(LogType.Warning, "Are you sure you want to proceed? (yes/no)");
#pragma warning disable CS8602
                    string? userResponse = Console.ReadLine().Trim().ToLower();
#pragma warning restore CS8602
                    if (userResponse == "yes")
                    {
                        try
                        {
                            string? skey = KeyCheckerHelper.GenerateStrongKey();
                            ConfigManager.UpdateSetting("app", "key", skey);
                            Program.logger.Log(LogType.Info, "We updated your settings");
                            Program.logger.Log(LogType.Info, $"Your key is: {skey}");
                            return true;
                        }
                        catch (Exception ex)
                        {
                            Program.logger.Log(LogType.Error, $"Failed to generate a key: {ex.Message}");

                        }
                    }
                    else if (userResponse == "no")
                    {
                        Program.logger.Log(LogType.Info, "Action cancelled.");
                        Environment.Exit(0x0);
                    }
                    else
                    {
                        Program.logger.Log(LogType.Info, "Invalid response. Please enter 'yes' or 'no'.");
                        Environment.Exit(0x0);
                    }

                    break;
                case "-environment:setup":
                    try
                    {
                        ConfigManager.Setup();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to start the built in setup: " + ex.Message);
                    }
                    break;
                case "-environment:maintenance":
                    try
                    {
                        ConfigManager.Maintenance();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to start the built in setup: " + ex.Message);
                    }
                    break;
                case "-environment:debug":
                    try
                    {
                        ConfigManager.Debug();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to start the built in setup: " + ex.Message);
                    }
                    break;
                case "-version":
                    Program.logger.Log(LogType.Info, $"You are running version: {Program.version}");
                    return true;
            }

            return false;
        }
    }

}