using MythicalClient;
using MythicalClient.Managers.LoggerManager;
using Salaros.Configuration;

namespace KosmaPanel.Managers.ConfigManager
{
    public class ConfigManager
    {

        public static string d_settings = "/var/www/mythicalclient/config.ini";

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