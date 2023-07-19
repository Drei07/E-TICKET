<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/student-class.php';

class ScanBarCode
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //scan barcode
    public function scanBarCode($barcode, $event_id)
    {

        // Check if the data already exists in the database
        $stmt = $this->runQuery('SELECT * FROM ticket WHERE barcode = :barcode AND event_id = :event_id');
        $stmt->execute(array(
            ":barcode" => $barcode,
            ":event_id" => $event_id,

        ));
        $barcodeExist = $stmt->fetch();

        if ($barcodeExist) {
            if ($barcodeExist['status'] === 'active') {

                // Update the status to 'disabled'
                $updateStmt = $this->runQuery('UPDATE ticket SET status = :status WHERE barcode = :barcode AND event_id = :event_id');
                $updateStmt->execute(array(
                    ":status" => 'disabled',
                    ":barcode" => $barcode,
                    ":event_id" => $event_id,
                ));

                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'You can now enter the event!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Barcode is not valid anymore!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 40000;
            }
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = "No barcode found";
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
        }
        header('Location: ../../../barcode-scanner');
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

//scan
if (isset($_POST['scan'])) {

    $barcode = trim($_POST['scan']);
    $event_id = $_GET['event_id'];

    $scan_barcode = new ScanBarCode();
    $scan_barcode->scanBarCode($barcode, $event_id);
}
