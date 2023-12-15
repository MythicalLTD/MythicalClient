namespace MythicalClient;

public enum LogType
{
    Info,
    Warning,
    Error
}

public class LoggerManager
{
    private string logFilePath;

    public LoggerManager()
    {
        string logDirectory = "logs";
        string logFileName = "log.txt";
        string logDirectoryPath = Path.Combine("/var/www/mythicalclient", logDirectory);
        logFilePath = Path.Combine(logDirectoryPath, logFileName);
        Directory.CreateDirectory(logDirectoryPath);
        RenameLogFile();
    }

    public void Log(LogType type, string message)
    {
        string timestamp = DateTime.Now.ToString("HH:mm:ss");
        string logText = $"[{timestamp}] [{type.ToString()}] {message}";

        ConsoleColor color = ConsoleColor.White;
        switch (type)
        {
            case LogType.Info:
                color = ConsoleColor.Blue;
                break;
            case LogType.Warning:
                color = ConsoleColor.Yellow;
                break;
            case LogType.Error:
                color = ConsoleColor.Red;
                break;
        }
        Console.ForegroundColor = color;
        Console.WriteLine(logText);
        Console.ResetColor();
        AppendToFile(logText);
    }
    public void PurgeLogs()
    {
        try
        {
            string logDirectory = "logs";
            string logDirectoryPath = Path.Combine("/var/www/mythicalclient", logDirectory);

            if (Directory.Exists(logDirectoryPath))
            {
                string[] logFiles = Directory.GetFiles(logDirectoryPath);
                foreach (string logFile in logFiles)
                {
                    File.Delete(logFile);
                }

                Console.ForegroundColor = ConsoleColor.Green;
                Console.WriteLine("All log files purged.");
                Console.ResetColor();
            }
            else
            {
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.WriteLine("Log directory not found.");
                Console.ResetColor();
            }
        }
        catch (Exception ex)
        {
            string timestamp = DateTime.Now.ToString("HH:mm:ss");
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine($"[{timestamp}] [Error] Error purging log files: {ex.Message}");
            Console.ResetColor();
        }
    }

    private void AppendToFile(string logText)
    {
        try
        {
            using StreamWriter writer = File.AppendText(logFilePath);
            writer.WriteLine(logText);
        }
        catch (Exception ex)
        {
            string timestamp = DateTime.Now.ToString("HH:mm:ss");
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine($"[{timestamp}] [Error] Error writing to log file: {ex.Message}");
            Console.ResetColor();
        }
    }

    private void RenameLogFile()
    {
        if (File.Exists(logFilePath))
        {
            string logFileNameWithoutExtension = Path.GetFileNameWithoutExtension(logFilePath);
            string logFileExtension = Path.GetExtension(logFilePath);
            #pragma warning disable
            string logDirectoryPath = Path.GetDirectoryName(logFilePath);
            string newLogFileName = GetUniqueLogFileName(logDirectoryPath, logFileNameWithoutExtension, logFileExtension);
            #pragma warning restore
            string newLogFilePath = Path.Combine(logDirectoryPath, newLogFileName);
            File.Move(logFilePath, newLogFilePath);
        }
    }

    private string GetUniqueLogFileName(string directoryPath, string fileNameWithoutExtension, string fileExtension)
    {
        string uniqueFileName = $"{fileNameWithoutExtension}-{DateTime.Now:yyyy-MM-dd}{fileExtension}";
        string uniqueFilePath = Path.Combine(directoryPath, uniqueFileName);

        int counter = 1;
        while (File.Exists(uniqueFilePath))
        {
            uniqueFileName = $"{fileNameWithoutExtension}-{counter++}-{DateTime.Now:yyyy-MM-dd}{fileExtension}";
            uniqueFilePath = Path.Combine(directoryPath, uniqueFileName);
        }

        return uniqueFileName;
    }
}

