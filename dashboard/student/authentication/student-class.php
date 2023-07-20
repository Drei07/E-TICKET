<?php
require_once __DIR__. '/../../../database/dbconfig.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once __DIR__.'/../../../configuration/settings-configuration.php';
require_once __DIR__. '/../../vendor/autoload.php';

class STUDENT
{
    private $conn;
    
    public function smtpEmail(){
        $smtp = new SystemConfig();
        $smtp_email = $smtp->getSmtpEmail();
        return $smtp_email;
      }
      
      public function smtpPassword(){
        $smtp = new SystemConfig();
        $smtp_password = $smtp->getSmtpPassword();
        return $smtp_password;
      }
      
      public function systemName(){
        $systemname = new SystemConfig();
        $Sname = $systemname->getSystemName();
        return $Sname;
      }

      public function mainUrl(){
        $main_url = new MainUrl();
        $URL = $main_url->getUrl();
        return $URL;
      }
      

    // Rest of the class methods...

    function sendEmail($email,$message,$subject,$smtp_email,$smtp_password,$system_name)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP(); 
        $mail->SMTPDebug  = 0;                     
        $mail->SMTPAuth   = true;                  
        $mail->SMTPSecure = "tls";                 
        $mail->Host       = "smtp.gmail.com";      
        $mail->Port       = 587; 
        $mail->AddAddress($email);
        $mail->Username = $smtp_email;  
        $mail->Password= $smtp_password;          
        $mail->SetFrom($smtp_email, $system_name);
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $imagePath = __DIR__ . '/../../../src/img/DCT-LOGO.png';
        $mail->AddEmbeddedImage($imagePath, 'logo', 'logo.png');
        $mail->Send();
       } 

       function sendEmailWithBarcode($email, $message, $subject, $pdfContent, $smtp_email, $smtp_password, $system_name)
       {
           $mail = new PHPMailer();
           $mail->IsSMTP();
           $mail->SMTPDebug  = 0;
           $mail->SMTPAuth   = true;
           $mail->SMTPSecure = "tls";
           $mail->Host       = "smtp.gmail.com";
           $mail->Port       = 587;
           $mail->ClearAllRecipients();
           $mail->AddAddress($email);
           $mail->Username = $smtp_email;
           $mail->Password = $smtp_password;
           $mail->SetFrom($smtp_email, $system_name);
           $mail->Subject    = $subject;
           $mail->MsgHTML($message);
           $mail->addStringAttachment($pdfContent, 'ticket.pdf');
           $mail->Send();
       }
       
       
    }


?>
