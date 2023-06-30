<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';
require_once '../../user/authentication/user-class.php';


//sub-admin controller

class sub_adminRegistration {
    private $sub_admin;
    private $main_url;
    private $smtp_email;
    private $smtp_password;
    private $system_name;


    public function __construct() {
        $this->sub_admin = new SUB_ADMIN();
        $this->main_url = $this->sub_admin->mainUrl();
        $this->smtp_email = $this->sub_admin->smtpEmail();
        $this->smtp_password = $this->sub_admin->smtpPassword();
        $this->system_name = $this->sub_admin->systemName();


    }

    // add sub-admin
    public function registerSub_admin($first_name, $middle_name, $last_name, $phone_number, $email, $hash_password, $tokencode, $user_type)
    {
        $stmt = $this->sub_admin->runQuery("SELECT * FROM users WHERE email=:email");
        $stmt->execute(array(":email"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Email already taken. Please try another one.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../sub-admin');
            exit();
        } else {
            if ($this->sub_admin->register($first_name, $middle_name, $last_name, $phone_number, $email, $hash_password, $tokencode, $user_type)) {
                $id = $this->sub_admin->lasdID();
                $key = base64_encode($id);
                $id = $key;

                $message = 
                "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Email Verification</title>
                    <style>
                        /* Define your CSS styles here */
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
                        <h1>Email Verification</h1>
                        <p>Hello, <strong>$email</strong></p>
                        <p>Welcome to $this->system_name</p>
                        Email:<br />$email <br />
                        Password:<br />$hash_password
                        <p>To complete your account registration, please click the button below to verify your email address:</p>
                        <p><a class='button' href='$this->main_url/verify-account?id=$id&code=$tokencode'>Verify Email</a></p>
                        <p>If you did not sign up for an account, you can safely ignore this email.</p>
                        <p>Thank you!</p>
                    </div>
                </body>
                </html>
                ";
                $subject = "Verify Email";

                $this->sub_admin->send_mail($email, $message, $subject, $this->smtp_email, $this->smtp_password, $this->system_name);
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Please check the Email to verify the account.";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;
                header('Location: ../sub-admin');
                exit();
            } else {
                // Error reporting
                $error = $sub_admin->getLastError();
                echo "Error: ".$error;

                $_SESSION['status_title'] = "Sorry !";
                $_SESSION['status'] = "Something went wrong, please try again!";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_timer'] = 10000000;
                header('Location: ../sub-admin');
                exit();
            }
        }
    }

    //edit sub-admin
    public function editSub_admin($user_id, $first_name, $middle_name, $last_name, $phone_number) {

        $stmt = $this->sub_admin->runQuery('SELECT first_name, middle_name, last_name, phone_number FROM users WHERE id=:id');
        $stmt->execute(array(':id' => $user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result['first_name'] === $first_name &&
            $result['middle_name'] === $middle_name &&
            $result['last_name'] === $last_name &&
            $result['phone_number'] === $phone_number) {
    
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
    
            header('Location: ../sub-admin');
            exit();
        }
    
        $stmt = $this->sub_admin->runQuery('UPDATE users SET first_name=:first_name, middle_name=:middle_name, last_name=:last_name, phone_number=:phone_number WHERE id=:id');
        $exec = $stmt->execute(array(
            ':id'             => $user_id,
            ':first_name'     => $first_name,
            ':middle_name'    => $middle_name,
            ':last_name'      => $last_name,
            ':phone_number'   => $phone_number,
        ));
    
        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Sub-Admin information successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    
        header('Location: ../sub-admin');
        exit();
    }    

    //disabled sub-admin
    public function disabledSub_admin($sub_admin_id){

        $disabled = "disabled";
        $stmt = $this->sub_admin->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"               => $sub_admin_id,
            ":account_status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Sub-Admin successfully disabled!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../sub-admin');
        exit();

    }

    //activate sub-admin
    public function activateSub_admin($sub_admin_id){
    
        $active = "active";
        $stmt = $this->sub_admin->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"                => $sub_admin_id,
            ":account_status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Sub-Admin successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../sub-admin');
        exit();

    }

}

//add sub-admin
if(isset($_POST['btn-add-sub-admin'])) {

    $first_name         = trim($_POST['first_name']);
    $middle_name        = trim($_POST['middle_name']);
    $last_name          = trim($_POST['last_name']);
    $phone_number       = trim($_POST['phone_number']);
    $email              = trim($_POST['email']);
    $user_type          = 2;
    $tokencode          = md5(uniqid(rand()));


    // Generate Password
    $varchar            = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $shuffle            = str_shuffle($varchar);
    $hash_password      = substr($shuffle,0,8);

    $sub_adminRegistration = new sub_adminRegistration();
    $sub_adminRegistration->registerSub_admin($first_name, $middle_name, $last_name, $phone_number, $email, $hash_password, $tokencode, $user_type);
}

//edit sub-admin
if(isset($_POST['btn-edit-sub-admin'])) {
    $user_id            = $_GET["id"];
    $first_name         = trim($_POST['first_name']);
    $middle_name        = trim($_POST['middle_name']);
    $last_name          = trim($_POST['last_name']);
    $phone_number       = trim($_POST['phone_number']);

    $sub_adminUpdate = new sub_adminRegistration();
    $sub_adminUpdate->editSub_admin($user_id, $first_name, $middle_name, $last_name, $phone_number);
}

//disabled sub-admin
if (isset($_GET['disabled_sub_admin'])) {
    $sub_admin_id = $_GET["id"];

    $disabled_sub_admin = new sub_adminRegistration();
    $disabled_sub_admin->disabledSub_admin($sub_admin_id);
}

//activate sub-admin
if (isset($_GET['activate_sub_admin'])) {
    $sub_admin_id = $_GET["id"];

    $activate_sub_admin = new sub_adminRegistration();
    $activate_sub_admin->activateSub_admin($sub_admin_id);
}
?>