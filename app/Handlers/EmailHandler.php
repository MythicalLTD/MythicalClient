<?php
namespace MythicalClient\Handlers;

use MythicalClient\App;
use MythicalClient\Handlers\ConfigHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHandler 
{
    /**
     * Send a verification email to the user
     * 
     * @param string $email The user email
     * @param string $code The verification code
     * @param string $first_name The user first name
     * @param string $last_name The user last name
     * 
     * @return bool Status
     */
    public static function SendVerification($email, $code, $first_name, $last_name)
    {

        $link = App::getUrl() . '/auth/verify?code=' . $code;
        $template = file_get_contents('../templates/email/verify.html');
        $placeholders = array('%VERIFY_LINK%', '%APP_URL%', '%APP_LOGO%', '%FIRST_NAME%', '%LAST_NAME%', '%APP_NAME%', '%SMTP_FROM%');
        $values = array($link, App::getUrl(),ConfigHandler::get("app","logo"),$first_name,$last_name,ConfigHandler::get("app","name"),ConfigHandler::get("mailserver", "fromemail"));
        $emailContent = str_replace($placeholders, $values, $template);

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = ConfigHandler::get("mailserver", "host");
            $mail->SMTPAuth = true;
            $mail->Username = ConfigHandler::get("mailserver", "username");
            $mail->Password = ConfigHandler::get("mailserver", "password");
            $mail->SMTPSecure = ConfigHandler::get("mailserver", "encryption");
            $mail->Port = ConfigHandler::get("mailserver", "port");

            $mail->setFrom(ConfigHandler::get("mailserver", "fromemail"), ConfigHandler::get("app", "name"));
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Verify your ' . ConfigHandler::get("app", "name") . ' account!';
            $mail->Body = $emailContent;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Send a reset password to user
     * 
     * @param string $email The user email
     * @param string $first_name The user first name
     * @param string $last_name The user last name
     * @param string $code The code for the user reset
     * 
     * @return bool Status
     */
    public static function SendReset($email,$first_name,$last_name,$code)
    {

        $link = App::getUrl() . '/auth/password/reset?code='.$code;
        $template = file_get_contents('../templates/email/reset-password.html');
        $placeholders = array('%APP_NAME%','%APP_LOGO%','%FIRST_NAME%','%LAST_NAME%','%RESET_LINK%');
        $values = array(ConfigHandler::get("app","name"),ConfigHandler::get("app","logo"),$first_name,$last_name,$link);
        $emailContent = str_replace($placeholders, $values, $template);

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = ConfigHandler::get("mailserver", "host");
            $mail->SMTPAuth = true;
            $mail->Username = ConfigHandler::get("mailserver", "username");
            $mail->Password = ConfigHandler::get("mailserver", "password");
            $mail->SMTPSecure = ConfigHandler::get("mailserver", "encryption");
            $mail->Port = ConfigHandler::get("mailserver", "port");

            $mail->setFrom(ConfigHandler::get("mailserver", "fromemail"), ConfigHandler::get("app", "name"));
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset your password on ' . ConfigHandler::get("app", "name");
            $mail->Body = $emailContent;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Send a login warning to user
     * 
     * @param string $email The user email
     * @param string $first_name The user first name
     * @param string $last_name The user last name
     * @param string $ip The ip address
     * @param string $iploc The location of the user
     * 
     * @return bool Status
     */
    public static function SendLogin($email,$first_name,$last_name,$ip,$iploc)
    {

        $link = App::getUrl() . '/account/reset/key';
        $template = file_get_contents('../templates/email/login.html');
        $placeholders = array('%APP_NAME%','%APP_LOGO%','%FIRST_NAME%','%LAST_NAME%','%IP_ADDRES%','%LOCATION%','%RESET_LINK%');
        $values = array(ConfigHandler::get("app","name"),ConfigHandler::get("app","logo"),$first_name,$last_name,$ip,$iploc,$link);
        $emailContent = str_replace($placeholders, $values, $template);

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = ConfigHandler::get("mailserver", "host");
            $mail->SMTPAuth = true;
            $mail->Username = ConfigHandler::get("mailserver", "username");
            $mail->Password = ConfigHandler::get("mailserver", "password");
            $mail->SMTPSecure = ConfigHandler::get("mailserver", "encryption");
            $mail->Port = ConfigHandler::get("mailserver", "port");

            $mail->setFrom(ConfigHandler::get("mailserver", "fromemail"), ConfigHandler::get("app", "name"));
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'New login on ' . ConfigHandler::get("app", "name");
            $mail->Body = $emailContent;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>