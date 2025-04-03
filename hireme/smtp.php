<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

class MailService
{
    function sendMail($email, $token)
    {
        //Hireme Email
        $hireme_mail = "hiremeapp722@gmail.com";

        //old Hireme App Password
        //$hireme_pass = "vqlfhepoenixetvs";

        //new hireme app Password
        $hireme_pass = "pmccmdmjyhmbyohm";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->Username = $hireme_mail;
        $mail->Password = $hireme_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;


        $mail->setFrom($hireme_mail, "Hire Me");
        $mail->addAddress($email);
        $mail->addReplyTo($hireme_mail, "Admin-hireme");
        $mail->IsHTML(true);
        $mail->Subject = "Complete your registration!";
        // URL to your logo image
        $logoUrl = 'hireme/hireme_logo1.png';

        // Email body with logo
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { width: 80%; margin: auto; text-align: center; }
                    .logo { margin-bottom: 20px; }
                    .content { margin: 20px 0; }
                    .footer { margin-top: 20px; font-size: 0.8em; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo'>
                        <img src='$logoUrl' alt='Company Logo' width='150'>
                    </div>
                    <div class='content'>
                        <p>Thank you for choosing HireMe-App! We hope we can help you find your dream job.</p>
                        <p>To proceed, please enter this code to complete your registration:</p>
                        <p><strong>OTP: $token</strong></p>
                        <p>If you need any further assistance, feel free to contact us at <a href='mailto:$hireme_mail'>$hireme_mail</a>.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date("Y") . " HireMe-App. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}