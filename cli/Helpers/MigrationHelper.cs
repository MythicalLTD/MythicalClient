using MySqlConnector;

namespace MythicalClient
{
    public class MigrationHelper
    {
        private static string MigrationConfigFilePath = "/var/www/mythicalclient/migrates.ini";
        public void Now()
        {
            if (ConfigManager.doesConfigExist() == true)
            {
                ExecuteScripts();
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like your main config file is missing!");
            }
        }
        private void ExecuteScript(MySqlConnection connection, string scriptContent)
        {
            using (var command = new MySqlCommand(scriptContent, connection))
            {
                command.ExecuteNonQuery();
            }
        }
        private void ExecuteScripts()
        {
            try
            {
                DatabaseHelper.getConnection();

                string[] scriptFiles = Directory.GetFiles("/var/www/mythicalclient/migrations/", "*.sql")
                    .OrderBy(scriptFile => Path.GetFileNameWithoutExtension(scriptFile))
                    .ToArray();

                HashSet<string> migratedScripts = ReadMigratedScripts();

                using (var connection = new MySqlConnection(DatabaseHelper.connectionString))
                {
                    connection.Open();

                    foreach (string scriptFile in scriptFiles)
                    {
                        string scriptContent = File.ReadAllText(scriptFile);
                        string scriptFileName = Path.GetFileName(scriptFile);

                        if (migratedScripts.Contains(scriptFileName))
                        {
                            Program.logger.Log(LogType.Info, $"Script {scriptFileName} was already migrated. Skipping.");
                            continue;
                        }

                        Program.logger.Log(LogType.Info, "Executing script: " + scriptFileName);
                        ExecuteScript(connection, scriptContent);

                        migratedScripts.Add(scriptFileName);
                        WriteMigratedScripts(migratedScripts);
                    }

                    connection.Close();
                }
            }
            catch (Exception ex)
            {
                Program.logger.Log(LogType.Error, "Migration error: " + ex.Message);
            }
        }
        private HashSet<string> ReadMigratedScripts()
        {
            HashSet<string> migratedScripts = new HashSet<string>();

            if (File.Exists(MigrationConfigFilePath))
            {
                using (StreamReader reader = new StreamReader(MigrationConfigFilePath))
                {
                    string? line;
                    while ((line = reader.ReadLine()) != null)
                    {
                        migratedScripts.Add(line.Trim());
                    }
                }
            }

            return migratedScripts;
        }

        private void WriteMigratedScripts(HashSet<string> migratedScripts)
        {
            using (StreamWriter writer = new StreamWriter(MigrationConfigFilePath))
            {
                foreach (string scriptFileName in migratedScripts)
                {
                    writer.WriteLine(scriptFileName);
                }
            }
        }
    }
}