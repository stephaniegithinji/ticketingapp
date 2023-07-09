<?php

// https://github.com/PHPMailer/PHPMailer
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

/**
 * Class MailSender
 *
 * The Mailer class is used by LawEnforceTech, a crime management system, to send emails.
 *
 * It uses a PHPMailer object and a Database object, with private properties for mailer properties
 * and a public property for accessing the database object.
 *
 * The __construct() method sets up the PHPMailer and Database objects,
 * and sets mailer properties based on values hardcoded.
 */
class Mailer
{
    // Constants
    const APP_NAME = 'TicketsAfrica';
    public static function logger(string $msg): void
    {
        $log = sprintf('[%s] [%s:%s] [%s] %s', date('D M d H:i:s', $time = microtime(true)) . sprintf('.%06d', ($time - floor($time)) * 1000000) . ' ' . date('Y', $time), 'php', 'warn', 'pid ' . getmypid(), $msg);
        error_log($log);
        file_put_contents('email_logs.txt', $log . PHP_EOL, FILE_APPEND);
    }

    public static function sendMail(string $to, string $subject, string $body): bool
    {
        try {
            // Initialize phpmailer
            $mail = new PHPMailer(true);

            // Configure SMTP
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->Username = "adm1n.tickectok@gmail.com";
            $mail->Password = "hzpaxrvijsxdawte";

            // Set mailer properties
            $mail->setFrom("adm1n.tickectok@gmail.com", self::APP_NAME);  //sender
            $mail->addAddress($to); //recepient
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->IsHTML(true);

            // Sending the email
            if (!$mail->send()) {
                self::logger('Sorry, something went wrong: ' . $mail->ErrorInfo);
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            // Catching errors
            self::logger('Email could not be sent. Mailer error: ' . $e->getMessage());
            throw new Exception('Email could not be sent. Mailer error: ' . $e->getMessage());
        }
    }
}
