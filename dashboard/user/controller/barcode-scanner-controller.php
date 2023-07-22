<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class BarCode {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function scannerSignin($password)
    {
        $stmt = $this->runQuery("SELECT * FROM event_access_key WHERE access_key = :access_key");
        $stmt->execute(array(":access_key" => $password));
        $accessKeyData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($accessKeyData) {
            $_SESSION['eventId'] = $accessKeyData['event_id'];

            if($accessKeyData['status'] == "disabled"){
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Access key is disabled';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 4000;
                header('Location: ../../../private/sub-admin/scan-barcode');
            }
            else{
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'You can now scan barcode!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;    
                header('Location: ../../../barcode-scanner');
            }
        } else {
            // Password does not match
            $_SESSION['status_title'] = 'Error';
            $_SESSION['status'] = 'Invalid access key';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 4000;
            header('Location: ../../../private/sub-admin/scan-barcode');
        }
    }
    


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//add
if (isset($_POST['btn-submit'])) {
    $password     = trim($_POST['password']);

    $barcode_scanner_signin = new BarCode();
    $barcode_scanner_signin->scannerSignin($password);
}


?>