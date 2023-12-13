using MythicalClient.Managers.LoggerManager;
using MythicalClient.Managers.ArgumentManager;

namespace MythicalClient;

public class Program
{
    public static string mcascii = @" 
  __  __       _   _     _           _  _____ _ _            _   
 |  \/  |     | | | |   (_)         | |/ ____| (_)          | |  
 | \  / |_   _| |_| |__  _  ___ __ _| | |    | |_  ___ _ __ | |_ 
 | |\/| | | | | __| '_ \| |/ __/ _` | | |    | | |/ _ \ '_ \| __|
 | |  | | |_| | |_| | | | | (_| (_| | | |____| | |  __/ | | | |_ 
 |_|  |_|\__, |\__|_| |_|_|\___\__,_|_|\_____|_|_|\___|_| |_|\__|
          __/ |                                                  
         |___/                                                   
    ";
    public static LoggerManager logger = new LoggerManager();
    public static string version = "0.0.0.1";

    public static void Main(string[] args)
    {
        try
        {
            Start(args);
        }
        catch (Exception ex)
        {
            logger.Log(LogType.Error, "Sorry but i cant start the daemon: " + ex.Message);
            Program.Stop();
        }
    }
    public static void Stop()
    {
        logger.Log(LogType.Info, "Please wait while we shut down the CLI.");
        Environment.Exit(0x0);
    }
    public static void Crash(string message)
    {
        logger.Log(LogType.Error, "We are sorry but the CLI crashed please make sure to report this to the support team: \n" + message);
        Environment.Exit(0x0);
    }
    private static void Start(string[] args)
    {
        Console.Clear();
        Directory.SetCurrentDirectory(AppDomain.CurrentDomain.BaseDirectory);
        Environment.CurrentDirectory = AppDomain.CurrentDomain.BaseDirectory;
        Console.WriteLine(mcascii);
        if (!OperatingSystem.IsLinux())
        {
            logger.Log(LogType.Error, "Sorry, but you have to be on Debian or Linux to use our daemon.");
            Program.Stop();
        }
        if (ArgumentManager.ProcessArguments(args))
        {
            Program.Stop();
        }
        logger.Log(LogType.Info, "Please use the -help function go get more info!");
        Program.Stop();
    }
}