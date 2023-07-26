<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/student-class.php';
require __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . '/tcpdf/tcpdf.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

class StudentController
{
    private $student;
    private $main_url;
    private $smtp_email;
    private $smtp_password;
    private $system_name;
    private $conn;


    public function __construct()
    {
        $this->student = new STUDENT();
        $this->main_url = $this->student->mainUrl();
        $this->smtp_email = $this->student->smtpEmail();
        $this->smtp_password = $this->student->smtpPassword();
        $this->system_name = $this->student->systemName();

        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function sendOTP($otp, $email)
    {
        if ($email == NULL) {
            $_SESSION['status_title'] = "Ooops";
            $_SESSION['status'] = "No email found, Please try again!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 40000;
            header('Location: ../../../');
            exit();
        } else {
            // Store OTP in session
            $_SESSION['OTP'] = $otp;

            $subject = "OTP Verification";
            $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>OTP Verification</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 30px;
                    background-color: #ffffff;
                    border-radius: 4px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                
                h1 {
                    color: #333333;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                
                p {
                    color: #666666;
                    font-size: 16px;
                    margin-bottom: 10px;
                }
                
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #0088cc;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 16px;
                    margin-top: 20px;
                }
                
                .logo {
                    display: block;
                    text-align: center;
                    margin-bottom: 30px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='logo'>
                    <img src='cid:logo' alt='Logo' width='150'>
                </div>
                <h1>OTP Verification</h1>
                <p>Hello, $email</p>
                <p>Your OTP is: $otp</p>
                <p>If you didn't request an OTP, please ignore this email.</p>
                <p>Thank you!</p>
            </div>
        </body>
        </html>";

            $this->student->sendEmail($email, $message, $subject, $this->smtp_email, $this->smtp_password, $this->system_name);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = "We've sent the OTP to $email";
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../../../verify-otp');
            exit;
        }
    }

    public function verifyOTP($otp)
    {
        if ($otp == $_SESSION['OTP']) {
            // Set the verified details in session
            $_SESSION['first_name'] = $_SESSION['not_verify_firstname'];
            $_SESSION['middle_name'] = $_SESSION['not_verify_middlename'];
            $_SESSION['last_name'] = $_SESSION['not_verify_lastname'];
            $_SESSION['phone_number'] = $_SESSION['not_verify_phone_number'];
            $_SESSION['email'] = $_SESSION['not_verify_email'];

            // Destroy the OTP in session
            unset($_SESSION['OTP']);

            $_SESSION['status_title'] = 'OTP is valid!';
            $_SESSION['status'] = "To gain access to the ticket, kindly enter the provided access token";
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
            $_SESSION['access-token'] = 1;

            // Unset the not verified details in session
            unset($_SESSION['not_verify_firstname']);
            unset($_SESSION['not_verify_middlename']);
            unset($_SESSION['not_verify_lastname']);
            unset($_SESSION['not_verify_phone_number']);
            unset($_SESSION['not_verify_email']);

            header('Location: ../../../access-token');
        } else if ($otp == NULL) {
            $_SESSION['status_title'] = "OTP is not found";
            $_SESSION['status'] = "It appears that the OTP you entered is invalid. Please try again!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 40000;
            header('Location: ../../../verify-otp');
            exit();
        } else {
            $_SESSION['status_title'] = "OTP is invalid";
            $_SESSION['status'] = "It appears that the OTP you entered is invalid. Please try again!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 40000;
            header('Location: ../../../verify-otp');
            exit();
        }
    }

    public function verifyAccessToken($access_token, $email, $date_access)
    {

        // Check if the data already exists in the database
        $stmt = $this->runQuery('SELECT * FROM access_token WHERE token = :token AND print_status = :print_status AND status = :status');
        $stmt->execute(array(
            ":token" => $access_token,
            ":print_status" => "printed",
            ":status" => "active",
        ));
        $token = $stmt->fetch();

        $access_token_id = $token['id'];

        if ($token) {
            $disabled = "disabled";
            $stmt = $this->runQuery('UPDATE access_token SET user_email = :user_email, date_of_use = :date_of_use, status=:status WHERE id=:id');
            $exec = $stmt->execute(array(
                ":id"        => $access_token_id,
                ":user_email" => $email,
                ":date_of_use" => $date_access,
                ":status"   => $disabled,
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'You can now print ticket';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
                $_SESSION['token'] = $access_token;
                unset($_SESSION['access-token']);
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }

            header('Location: ../../../get-ticket');
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Access Token is invalid, Please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../access-token');
        }
    }

    public function getTicketMandatory($eventIds, $courseId, $yearLevelId, $first_name, $middle_name, $last_name, $phone_number, $email)
    {
        // Generate a unique barcode
        $barcode = $this->generateUniqueBarcode();

        // Generate the barcode image
        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);

        // Save the barcode image to a file
        $barcodeImagePath = '../barcode/barcode.png';
        file_put_contents($barcodeImagePath, $barcodeImage);

        foreach ($eventIds as $eventId) {
            // Insert the ticket data into the database
            $stmt = $this->runQuery('INSERT INTO ticket (event_id, course_id, year_level_id, barcode, user_first_name, user_middle_name, user_last_name, user_phone_number, user_email) VALUES (:event_id, :course_id, :year_level_id, :barcode, :user_first_name, :user_middle_name, :user_last_name, :user_phone_number, :user_email)');
            $exec = $stmt->execute(array(
                ":event_id" => $eventId,
                ":course_id" => $courseId,
                ":year_level_id" => $yearLevelId,
                ":barcode" => $barcode,
                ":user_first_name" => $first_name,
                ":user_middle_name" => $middle_name,
                ":user_last_name" => $last_name,
                ":user_phone_number" => $phone_number,
                ":user_email" => $email
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = "We've sent the ticket to $email";
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;


            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        // Create the HTML content for the ticket
        // Read the content of the HTML file
        ob_start();
        include('../ticket.php');
        $htmlContent = ob_get_clean();

        // Replace the placeholder with the actual barcode image source
        $htmlContent = str_replace('cid:barcode', $barcodeImagePath, $htmlContent);

        // Create the PDF using TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->AddPage();

        // Embed the barcode image in the PDF
        // Get the dimensions of the PDF page
        $pageWidth = $pdf->getPageWidth();

        // Get the dimensions of the barcode image
        $imageWidth = 150;
        $imageHeight = 30; // Auto height based on the image aspect ratio

        // Embed the barcode image in the PDF
        // Get the dimensions of the PDF page
        $pageWidth = $pdf->getPageWidth();

        // Get the dimensions of the barcode image
        $imageWidth = 180;
        $imageHeight = 60; // Auto height based on the image aspect ratio

        // Calculate the coordinates to position the image at the top middle
        $imageX = ($pageWidth - $imageWidth) / 2;
        $imageY = 20; // Adjust the vertical position (20) as needed

        // Embed the barcode image in the PDF
        $pdf->Image($barcodeImagePath, $imageX, $imageY, $imageWidth, $imageHeight, '', '', 'N', false, 300, '', false, false, 0);
    
        // Set the HTML content as the PDF body
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Output the PDF as a string
        $pdfContent = $pdf->Output('ticket.pdf', 'S');


        // Send the email with the PDF attachment
        $subject = "Event Ticket";
        $message = "Hello [$first_name],<br><br>We are pleased to inform you that your ticket for the event is now ready. Attached to this email, you will find your ticket with a barcode.<br><br>Kindly ensure that you have this ticket with you when attending the event, as it will serve as your entry pass.<br><br>Should you have any further questions or concerns, please do not hesitate to contact us.<br><br>Best regards,<br>[DCT E-CKET]";

       $this->student->sendEmailWithBarcode($email, $message, $subject, $pdfContent, $this->smtp_email, $this->smtp_password, $this->system_name);
        
            unset($_SESSION['token']);
            unset($_SESSION['firstname']);
            unset($_SESSION['middlename']);
            unset($_SESSION['lastname']);
            unset($_SESSION['phone_number']);
            unset($_SESSION['email']);
        header('Location: ../../../');
    }

    public function getTicketOptional($eventId, $first_name, $middle_name, $last_name, $phone_number, $email){
        // Generate a unique barcode
        $barcode = $this->generateUniqueBarcode();

        // Generate the barcode image
        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);

        // Save the barcode image to a file
        $barcodeImagePath = '../barcode/barcode.png';
        file_put_contents($barcodeImagePath, $barcodeImage);

            // Insert the ticket data into the database
            $stmt = $this->runQuery('INSERT INTO ticket (event_id, barcode, user_first_name, user_middle_name, user_last_name, user_phone_number, user_email) VALUES (:event_id, :barcode, :user_first_name, :user_middle_name, :user_last_name, :user_phone_number, :user_email)');
            $exec = $stmt->execute(array(
                ":event_id" => $eventId,
                ":barcode" => $barcode,
                ":user_first_name" => $first_name,
                ":user_middle_name" => $middle_name,
                ":user_last_name" => $last_name,
                ":user_phone_number" => $phone_number,
                ":user_email" => $email
            ));

            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = "We've sent the ticket to $email";
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;


            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }

        // Create the HTML content for the ticket
        // Read the content of the HTML file
        ob_start();
        include('../ticket.php');
        $htmlContent = ob_get_clean();

        // Replace the placeholder with the actual barcode image source
        $htmlContent = str_replace('cid:barcode', $barcodeImagePath, $htmlContent);

        // Create the PDF using TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->AddPage();

        // Embed the barcode image in the PDF
        // Get the dimensions of the PDF page
        $pageWidth = $pdf->getPageWidth();

        // Get the dimensions of the barcode image
        $imageWidth = 150;
        $imageHeight = 30; // Auto height based on the image aspect ratio

        // Embed the barcode image in the PDF
        // Get the dimensions of the PDF page
        $pageWidth = $pdf->getPageWidth();

        // Get the dimensions of the barcode image
        $imageWidth = 180;
        $imageHeight = 60; // Auto height based on the image aspect ratio

        // Calculate the coordinates to position the image at the top middle
        $imageX = ($pageWidth - $imageWidth) / 2;
        $imageY = 20; // Adjust the vertical position (20) as needed

        // Embed the barcode image in the PDF
        $pdf->Image($barcodeImagePath, $imageX, $imageY, $imageWidth, $imageHeight, '', '', 'N', false, 300, '', false, false, 0);
    
        // Set the HTML content as the PDF body
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Output the PDF as a string
        $pdfContent = $pdf->Output('ticket.pdf', 'S');


        // Send the email with the PDF attachment
        $subject = "Event Ticket";
        $message = "Hello [$first_name],<br><br>We are pleased to inform you that your ticket for the event is now ready. Attached to this email, you will find your ticket with a barcode.<br><br>Kindly ensure that you have this ticket with you when attending the event, as it will serve as your entry pass.<br><br>Should you have any further questions or concerns, please do not hesitate to contact us.<br><br>Best regards,<br>[DCT E-CKET]";
       $this->student->sendEmailWithBarcode($email, $message, $subject, $pdfContent, $this->smtp_email, $this->smtp_password, $this->system_name);
        
        unset($_SESSION['token']);
        unset($_SESSION['firstname']);
        unset($_SESSION['middlename']);
        unset($_SESSION['lastname']);
        unset($_SESSION['phone_number']);
        unset($_SESSION['email']);
        header('Location: ../../../');
    }

    private function generateUniqueBarcode()
    {
        // Generate a unique barcode
        $barcode = rand(1000000000, 9999999999);

        // Check if the barcode already exists in the database
        $stmt = $this->runQuery('SELECT COUNT(*) FROM ticket WHERE barcode = :barcode');
        $stmt->execute(array(":barcode" => $barcode));
        $count = $stmt->fetchColumn();

        // If the barcode is not unique, regenerate it
        while ($count > 0) {
            $barcode = rand(1000000000, 9999999999);
            $stmt->execute(array(":barcode" => $barcode));
            $count = $stmt->fetchColumn();
        }

        return $barcode;
    }

    public function cancelTicket(){
        unset($_SESSION['token']);
        unset($_SESSION['firstname']);
        unset($_SESSION['middlename']);
        unset($_SESSION['lastname']);
        unset($_SESSION['phone_number']);
        unset($_SESSION['email']);
        header('Location: ../../../');
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}


//pre register the details
if (isset($_POST['btn-pre-register'])) {
    $_SESSION['not_verify_firstname']       = trim($_POST['first_name']);
    $_SESSION['not_verify_middlename']      = trim($_POST['middle_name']);
    $_SESSION['not_verify_lastname']        = trim($_POST['last_name']);
    $_SESSION['not_verify_phone_number']    = trim($_POST['phone_number']);
    $_SESSION['not_verify_email']           = trim($_POST['email']);

    $email                                  = $_SESSION['not_verify_email'];
    //generate OTP
    $otp = rand(100000, 999999);

    $send_otp = new StudentController();
    $send_otp->sendOTP($otp, $email);
}

//verify email through OTP
if (isset($_POST['btn-verify-otp'])) {

    $otp = trim($_POST['verify_otp']);

    $verify_otp = new StudentController();
    $verify_otp->verifyOTP($otp);
}

//resend OTP
if (isset($_GET['btn-resend-otp'])) {
    $email  = $_SESSION['not_verify_email'];
    //generate OTP
    $otp = rand(100000, 999999);

    $send_otp = new StudentController();
    $send_otp->sendOTP($otp, $email);
}

//access token
if (isset($_POST['btn-access-token'])) {
    date_default_timezone_set('Asia/Manila');

    $access_token = trim($_POST['access_token']);
    $email = $_SESSION['email'];
    $date_access = date('Y-m-d H:i:s');

    $verify_access_token = new StudentController();
    $verify_access_token->verifyAccessToken($access_token, $email, $date_access);
}

if (isset($_POST['btn-get-ticket-mandatory'])) {
    // Get the event IDs from the form
    $eventIds = $_POST['event_ids'];

    // Get the course ID and year level ID
    $courseId = $_POST['course_id'];
    $yearLevelId = $_POST['year_level_id'];

    $first_name     =   trim($_POST['first_name']);
    $middle_name    =   trim($_POST['middle_name']);
    $last_name      =   trim($_POST['last_name']);
    $phone_number   =   trim($_POST['phone_number']);
    $email          =   $_SESSION['email'];

    $get_ticket_mandatory = new StudentController();
    $get_ticket_mandatory->getTicketMandatory($eventIds, $courseId, $yearLevelId, $first_name, $middle_name, $last_name, $phone_number, $email);
}

if (isset($_POST['btn-get-ticket-optional'])) {
    // Get the event IDs from the form
    $eventId       =   trim($_POST['event_id']);
    $first_name     =   trim($_POST['first_name']);
    $middle_name    =   trim($_POST['middle_name']);
    $last_name      =   trim($_POST['last_name']);
    $phone_number   =   trim($_POST['phone_number']);
    $email          =   $_SESSION['email'];

    $get_ticket_optional = new StudentController();
    $get_ticket_optional->getTicketOptional($eventId, $first_name, $middle_name, $last_name, $phone_number, $email);
}

//cancel ticket
if(isset($_GET['cancel_ticket'])){

    $cancel_ticket = new StudentController();
    $cancel_ticket->cancelTicket();

}
