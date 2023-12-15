using Salaros.Configuration;

namespace MythicalClient
{
    public class ConfigManager
    {

        public static string d_settings = "/var/www/mythicalclient/config.ini";
        public static string d_example_settings_file = "/var/www/mythicalclient/templates/settings.ini";
        public static bool doesConfigExist()
        {
            if (File.Exists(d_settings))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public static void Setup()
        {
            try
            {
                if (doesConfigExist() == false)
                {
                    using (StreamWriter sw = File.CreateText(d_settings))
                    {
                        string content = File.ReadAllText(d_example_settings_file);
                        sw.Write(content);
                    }
                    Program.logger.Log(LogType.Info, "Hi, and welcome to the automated installer for MythicalClient.");
                    Program.logger.Log(LogType.Info, "This installer will help you set up your client with no problem and is easy to follow. \n");
                    Console.Write("Name [");
                    Console.ForegroundColor = ConsoleColor.Yellow;
                    Console.Write($"MythicalSystems");
                    Console.ResetColor();
                    Console.Write("]: ");

                    string? name = Console.ReadLine();
                    if (string.IsNullOrWhiteSpace(name))
                    {
                        name = "MythicalSystems";
                    }

                    Console.Write("Logo [");
                    Console.ForegroundColor = ConsoleColor.Yellow;
                    Console.Write($"https://avatars.githubusercontent.com/u/117385445");
                    Console.ResetColor();
                    Console.Write("]: ");

                    string? logo = Console.ReadLine();
                    if (string.IsNullOrWhiteSpace(logo))
                    {
                        logo = "https://avatars.githubusercontent.com/u/117385445";
                    }

                    Console.Write("Language [");
                    Console.ForegroundColor = ConsoleColor.Yellow;
                    Console.Write($"en_US");
                    Console.ResetColor();
                    Console.Write("]: ");

                    string? lang = Console.ReadLine();
                    if (string.IsNullOrWhiteSpace(lang))
                    {
                        lang = "en_US";
                    }

                    Console.Write("Support Email [");
                    Console.ForegroundColor = ConsoleColor.Yellow;
                    Console.Write($"support@changeme.net");
                    Console.ResetColor();
                    Console.Write("]: ");

                    string? support_email = Console.ReadLine();
                    if (string.IsNullOrWhiteSpace(support_email))
                    {
                        support_email = "support@changeme.net";
                    }

                    Console.Write("Debug [");
                    Console.ForegroundColor = ConsoleColor.Yellow;
                    Console.Write($"false");
                    Console.ResetColor();
                    Console.Write("]: ");

                    string? debug = Console.ReadLine();
                    if (string.IsNullOrWhiteSpace(debug))
                    {
                        debug = "false";
                    }


                    try
                    {
                        UpdateSetting("app", "name", $"'{name}'");
                        UpdateSetting("app", "logo", $"'{logo}'");
                        UpdateSetting("app", "lang", $"'{lang}'");
                        UpdateSetting("landingpage", "support_email", $"'{support_email}'");
                        UpdateSetting("app", "debug", $"'{debug}'");
                        Program.logger.Log(LogType.Info, "We saved the settings inside the setting file!");
                        Program.Stop();
                    }
                    catch (Exception ex)
                    {
                        Program.logger.Log(LogType.Error, "Sorry but the auto settings throws this error: " + ex.Message);
                    }

                }
                else
                {
                    Program.logger.Log(LogType.Warning, "We found a config file please delete it first if you want to reconfigure your client!");
                    Program.Stop();
                }
            }
            catch (Exception ex)
            {
                Program.logger.Log(LogType.Error, "Something failed: " + ex.ToString());
                Program.Stop();
            }
        }
        public static void Maintenance()
        {
            string? isMaintenance = GetSetting("app", "maintenance");
            if (isMaintenance == "'true'")
            {
                UpdateSetting("app", "maintenance", "'false'");
            }
            else
            {
                UpdateSetting("app", "maintenance", "'true'");
            }
            Program.Stop();
        }
        public static void Debug()
        {
            string? isDebug = GetSetting("app", "debug");
            if (isDebug == "'true'")
            {
                UpdateSetting("app", "debug", "'false'");
            }
            else
            {
                UpdateSetting("app", "debug", "'true'");
            }
            Program.Stop();
        }
        public static string? GetSetting(string app, string setting)
        {
            try
            {
                var cfg = new ConfigParser(d_settings);
                var st = cfg.GetValue(app, setting);
                return st;
            }
            catch (Exception ex)
            {
                Program.logger.Log(LogType.Error, "Failed to get setting: " + ex.Message);
                Program.Stop();
                return null;
            }
        }

        public static void UpdateSetting(string app, string setting, string value)
        {
            try
            {
                var cfg = new ConfigParser(d_settings);
                cfg.SetValue(app, setting, value);
                cfg.Save();
                Program.logger.Log(LogType.Info, $"Updated: {setting}");
            }
            catch (Exception ex)
            {
                Program.logger.Log(LogType.Error, "Failed to update settings: " + ex.Message);
                Program.Stop();
            }
        }
    }

}