using MythicalClient.Managers.LoggerManager;
using MythicalClient;

namespace MythicalClient.Managers.ArgumentManager
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
                //case "-migrate":
                //    try
                //    {
                //        MigrationHelper mg = new MigrationHelper();
                //        mg.Now();
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, "Failed to migrate: " + ex.Message);
                //    }
                //    break;
                //case "-environment:database":
                //    try
                //    {
                //        ConfigurationHelper.StartDBGUI();
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, "Failed: " + ex.Message);
                //    }
                //    break;
                //case "-environment:setup":
                //    try
                //    {
                //        ConfigurationHelper.StartSetup();
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, "Failed: " + ex.Message);
                //    }
                //    break;
                //case "-environment:ssh":
                //    try
                //    {
                //        ConfigurationHelper.StartSSHGUI();
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, "Failed: " + ex.Message);
                //    }
                //    break;
                case "-help":
                    try
                    {
                        Console.WriteLine("╔≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ KosmaPanel CLI ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╗");
                        Console.WriteLine("‖                                                                                 ‖");
                        Console.WriteLine("‖    -help                   ‖ Opens a help menu with the available commands.     ‖");
                        Console.WriteLine("‖    -version                ‖ See the version / build version of the CLI.        ‖");
                        Console.WriteLine("‖    -environment:setup      ‖ Do a quick setup for the webserver.                ‖");
                        Console.WriteLine("‖    -environment:database   ‖ Do a quick setup for the database.                 ‖");
                        Console.WriteLine("‖    -environment:ssh        ‖ Do a quick setup for the ssh-server.               ‖");
                        Console.WriteLine("‖    -migrate                ‖ Create and setup all tables in the database        ‖");
                        Console.WriteLine("‖    -key:generate           ‖ Generate a new encryption key for KosmaPanel.      ‖");
                        Console.WriteLine("‖    -logs:clear             ‖ Delete all the cached logs for KosmaPanel.         ‖");
                        Console.WriteLine("‖                                                                                 ‖");
                        Console.WriteLine("╚≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ Copyright 2023 MythicalSystems ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╝");
                        return true;
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Failed to display help message: " + ex.Message);
                    }
                    break;
                //case "-logs:clear":
                //    try
                //    {
                //        Program.logger.PurgeLogs();
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, "Failed to purge the logs: " + ex.Message);
                //
                //    }
                //    break;

                //case "-key:generate":
                //    ConfigManager.ConfigManager.d_settings = Directory.GetCurrentDirectory() + @"/config.ini";
                //    try
                //    {
                //        string skey = KeyChecker.GenerateStrongKey();
                //        ConfigManager.ConfigManager.UpdateSetting("Daemon", "key", skey);
                //        Program.logger.Log(LogType.Info, "We updated your daemon settings");
                //        Program.logger.Log(LogType.Info, $"Your key is: {skey}");
                //        return true;
                //    }
                //    catch (Exception ex)
                //    {
                //        Program.logger.Log(LogType.Error, $"Failed to generate a key: {ex.Message}");
                //
                //    }
                //    break;
                case "-version":
                    Program.logger.Log(LogType.Info, $"You are running version: {Program.version}");
                    return true;
            }

            return false; 
        }
    }

}