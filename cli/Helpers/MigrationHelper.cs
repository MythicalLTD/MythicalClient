using MySqlConnector;
using YamlDotNet.Serialization;

namespace MythicalClient
{
    public class MigrationHelper
    {
        FileManager fm = new FileManager();
        private static string MigrationConfigFilePath = "migrates.ini";
        public static string? connectionString;

        public void Now()
        {
            if (fm.MFolderExists() == true)
            {
                ExecuteScripts();
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like you are missing some important core files; please redownload MythicalDash!!");
            }
        }
        private void getConnection()
        {
            if (fm.ConfigExists() == true)
            {
                string filePath = "config.yml";
                string yamlContent = File.ReadAllText(filePath);

                var deserializer = new DeserializerBuilder().Build();
                var yamlObject = deserializer.Deserialize(new StringReader(yamlContent));
                var databaseSettings = (yamlObject as dynamic)["database"];
                string dbHost = databaseSettings["host"];
                string dbPort = databaseSettings["port"];
                string dbUsername = databaseSettings["username"];
                string dbPassword = databaseSettings["password"];
                string dbName = databaseSettings["database"];
                connectionString = $"Server={dbHost};Port={dbPort};User ID={dbUsername};Password={dbPassword};Database={dbName}";
            }
            else
            {
                Program.logger.Log(LogType.Error, "It looks like the config file does not exist!");
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
                getConnection();

                string[] scriptFiles = Directory.GetFiles("migrate/", "*.sql")
                    .OrderBy(scriptFile => Convert.ToInt32(Path.GetFileNameWithoutExtension(scriptFile)))
                    .ToArray();

                HashSet<string> migratedScripts = ReadMigratedScripts();

                using (var connection = new MySqlConnection(connectionString))
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
                    string line;
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