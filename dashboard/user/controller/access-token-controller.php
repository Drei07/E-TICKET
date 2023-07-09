<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';

require __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

class AccessTokenMandatory
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function addAccessTokenMandatory($user_id, $course_id, $year_level_id, $number_of_tokens)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tokenLength = 10;

        $accessTokens = array();
        $characterCount = strlen($characters);

        while (count($accessTokens) < $number_of_tokens) {
            $accessToken = '';
            for ($j = 0; $j < $tokenLength; $j++) {
                $randomIndex = mt_rand(0, $characterCount - 1);
                $accessToken .= $characters[$randomIndex];
            }

            if (!$this->isTokenExistsMandatory($accessToken)) {
                $accessTokens[] = $accessToken;
            }
        }

        // Insert the generated tokens into the database
        $values = array();
        $sql = 'INSERT INTO access_token (user_id, course_id, year_level_id, token) VALUES ';
        foreach ($accessTokens as $token) {
            $values[] = "(:user_id, :course_id, :year_level_id, :token_$token)";
        }
        $sql .= implode(', ', $values);

        $stmt = $this->runQuery($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':course_id', $course_id);
        $stmt->bindValue(':year_level_id', $year_level_id);


        foreach ($accessTokens as $index => $token) {
            $param = ":token_$token";
            $stmt->bindValue($param, $token);
        }

        $exec = $stmt->execute();

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Successfully generated access tokens';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../course-events-list');
        exit();

        return $accessTokens;
    }

    public function isTokenExistsMandatory($accessToken)
    {
        $stmt = $this->runQuery('SELECT COUNT(*) FROM access_token WHERE token = :token');
        $stmt->execute(array(':token' => $accessToken));
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }

    public function generateAccessTokensPDFMandatory($course_id, $year_level_id)
    {
        //select course name
        $stmt = $this->runQuery('SELECT * FROM course WHERE id = :id');
        $stmt->execute(array(':id' => $course_id));
        $course_data = $stmt->fetch(PDO::FETCH_ASSOC);

        //select year level id
        $stmt = $this->runQuery('SELECT * FROM year_level WHERE id = :id');
        $stmt->execute(array(':id' => $year_level_id));
        $year_level_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Select access tokens with matching course, year, active status, and pending print_status
        $stmt = $this->runQuery('SELECT * FROM access_token WHERE course_id = :course_id AND year_level_id = :year_level_id AND status = "active" AND print_status = "pending"');
        $stmt->execute(array(':course_id' => $course_id, ':year_level_id' => $year_level_id));
        $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($tokens) > 0) {
            // Create the HTML content for the PDF
            $html = '
            <style>
                .head {
                    display: flex;
                    justify-content: center;
                }
                .head img {
                    width: 70px;
                    height: 70px;
                }
                .head2 {
                    display: block;
                    text-align: center;
                    padding: 10px;
                }
                .title {
                    display: block;
                    text-align: center;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid black;
                    padding: 5px;
                    text-align: center;
                }
            </style>
            <div class="head">
                <div class="head2">
                    <h1>DOMINICAN COLLEGE OF TARLAC</h1>
                    <p>Mac Arthur Highway, Poblacion (Sto.Cristo), Capas. 2315 Tarlac, Philippinnes</p>
                    <p>Tel.No. (045) 491-7578/Telefax (045) 925-0519</p>
                    <p>E-mail: domct2315@yahoo.com</p>
                </div>
            </div>
            <div class="title">
            <h2>' . $course_data['course'] . '</h2>
                <h3>' . $year_level_data['year_level'] . '</h3>
                <h4>MANDATORY EVENT ACCESS TOKENS</h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Access Tokens</th>
                        <th>Date Printed</th>
                    </tr>
                </thead>
                <tbody>';

            $number = 1;
            foreach ($tokens as $token) {
                $datePrinted = date('m/d/y (h:i:s A)', time());
                $html .= '
                    <tr>
                        <td>' . $number . '</td>
                        <td>' . $token['token'] . '</td>
                        <td>' . $datePrinted . '</td>
                    </tr>';
                $number++;
            }

            $html .= '
                </tbody>
            </table>
            ';



            // Create a new Dompdf instance
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            // Render the HTML content to PDF
            $dompdf->render();

            // Save the PDF file
            $courseName = str_replace(' ', '_', $course_data['course']);
            $yearLevel = str_replace(' ', '_', $year_level_data['year_level']);
            $timestamp = time();
            $filename = ''.$courseName.'_'.$yearLevel.'_access_tokens_'.$timestamp.'.pdf';
            $output = $dompdf->output();
            file_put_contents($filename, $output);

            // Specify the backup folder path
            $backupFolderPath = '../../pdf/';

            // Move the PDF file to the backup folder
            $backupFilename = $backupFolderPath . $filename;
            rename($filename, $backupFilename);

            // Update the print_status of the generated tokens
            $tokenIds = array_column($tokens, 'id');
            $this->updatePrintStatusMandatory($tokenIds);

            // Save the PDF filename in the database
            $this->savePdfFilenameToDatabaseMandatory($course_id, $year_level_id, $filename);

            // Provide the link to download the PDF file
            if (file_exists($backupFilename)) {
                // Download the PDF file
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment;filename=' . basename($backupFilename));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($backupFilename));
                ob_clean();
                flush();
            
                // Read the file and output its contents
                readfile($backupFilename);
        
                exit();
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
                header('Location: ../course-events-list');
                exit();
            }
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Access token are printed already, try to generate again thank you!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
            header('Location: ../course-events-list');
            exit();
        }
    }

    public function updatePrintStatusMandatory($tokenIds)
    {
        // Update the print_status of the given token IDs to "printed"
        $placeholders = implode(',', array_fill(0, count($tokenIds), '?'));
        $sql = "UPDATE access_token SET print_status = 'printed' WHERE id IN ($placeholders)";
        $stmt = $this->runQuery($sql);
        $stmt->execute($tokenIds);
    }

    public function savePdfFilenameToDatabaseMandatory($course_id, $year_level_id, $filename)
    {
        $stmt = $this->runQuery('INSERT INTO pdf_file (course_id, year_level_id, file_name) VALUES (:course_id, :year_level_id, :file_name)');
        $exec = $stmt->execute(array(
            ":course_id"  => $course_id,
            ":year_level_id"  => $year_level_id,
            ":file_name"         => $filename,
        ));
    
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Add access tokens Mandatory
if (isset($_POST['btn-add-access-token-mandatory'])) {
    $user_id = $_GET["user_id"];
    $course_id = $_GET["course_id"];
    $year_level_id = $_GET["year_level_id"];
    $number_of_tokens = trim($_POST['access_token']);

    $add_access_token = new AccessTokenMandatory();
    $generated_tokens = $add_access_token->addAccessTokenMandatory($user_id, $course_id, $year_level_id, $number_of_tokens);
}

// Generate access tokens PDF and provide download link Mandatory
if (isset($_GET['print_access_tokens-mandatory'])) {
    $course_id = $_GET["course_id"];
    $year_level_id = $_GET["year_level_id"];

    $print_access_token = new AccessTokenMandatory();
    $print_access_token->generateAccessTokensPDFMandatory($course_id, $year_level_id);
}

class AccessTokenOptional
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function addAccessTokenoOptional($user_id, $event_id, $number_of_tokens)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tokenLength = 10;

        $accessTokens = array();
        $characterCount = strlen($characters);

        while (count($accessTokens) < $number_of_tokens) {
            $accessToken = '';
            for ($j = 0; $j < $tokenLength; $j++) {
                $randomIndex = mt_rand(0, $characterCount - 1);
                $accessToken .= $characters[$randomIndex];
            }

            if (!$this->isTokenExistsOptional($accessToken)) {
                $accessTokens[] = $accessToken;
            }
        }

        // Insert the generated tokens into the database
        $values = array();
        $sql = 'INSERT INTO access_token (user_id, event_id, token) VALUES ';
        foreach ($accessTokens as $token) {
            $values[] = "(:user_id, :event_id, :token_$token)";
        }
        $sql .= implode(', ', $values);

        $stmt = $this->runQuery($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':event_id', $event_id);


        foreach ($accessTokens as $index => $token) {
            $param = ":token_$token";
            $stmt->bindValue($param, $token);
        }

        $exec = $stmt->execute();

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Successfully generated access tokens';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../events-details');
        exit();

        return $accessTokens;
    }

    public function isTokenExistsOptional($accessToken)
    {
        $stmt = $this->runQuery('SELECT COUNT(*) FROM access_token WHERE token = :token');
        $stmt->execute(array(':token' => $accessToken));
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }

    public function generateAccessTokensPDFOptional($event_id)
    {
        //select course name
        $stmt = $this->runQuery('SELECT * FROM events WHERE id = :id');
        $stmt->execute(array(':id' => $event_id));
        $event_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Select access tokens with matching course, year, active status, and pending print_status
        $stmt = $this->runQuery('SELECT * FROM access_token WHERE event_id = :event_id AND status = "active" AND print_status = "pending"');
        $stmt->execute(array(':event_id' => $event_id));
        $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($tokens) > 0) {
            // Create the HTML content for the PDF
            $html = '
            <style>
                .head {
                    display: flex;
                    justify-content: center;
                }
                .head img {
                    width: 70px;
                    height: 70px;
                }
                .head2 {
                    display: block;
                    text-align: center;
                    padding: 10px;
                }
                .title {
                    display: block;
                    text-align: center;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid black;
                    padding: 5px;
                    text-align: center;
                }
            </style>
            <div class="head">
                <div class="head2">
                    <h1>DOMINICAN COLLEGE OF TARLAC</h1>
                    <p>Mac Arthur Highway, Poblacion (Sto.Cristo), Capas. 2315 Tarlac, Philippinnes</p>
                    <p>Tel.No. (045) 491-7578/Telefax (045) 925-0519</p>
                    <p>E-mail: domct2315@yahoo.com</p>
                </div>
            </div>
            <div class="title">
            <h2>' . $event_data['event_name'] . '</h2>
                <h4>OPTIONAL EVENT ACCESS TOKENS</h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Access Tokens</th>
                        <th>Date Printed</th>
                    </tr>
                </thead>
                <tbody>';

            $number = 1;
            foreach ($tokens as $token) {
                $datePrinted = date('m/d/y (h:i:s A)', time());
                $html .= '
                    <tr>
                        <td>' . $number . '</td>
                        <td>' . $token['token'] . '</td>
                        <td>' . $datePrinted . '</td>
                    </tr>';
                $number++;
            }

            $html .= '
                </tbody>
            </table>
            ';



            // Create a new Dompdf instance
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            // Render the HTML content to PDF
            $dompdf->render();

            // Save the PDF file
            $event_name = str_replace(' ', '_', $event_data['event_name']);
            $timestamp = time();
            $filename = ''.$event_name.'_access_tokens_'.$timestamp.'.pdf';
            $output = $dompdf->output();
            file_put_contents($filename, $output);

            // Specify the backup folder path
            $backupFolderPath = '../../pdf/';

            // Move the PDF file to the backup folder
            $backupFilename = $backupFolderPath . $filename;
            rename($filename, $backupFilename);

            // Update the print_status of the generated tokens
            $tokenIds = array_column($tokens, 'id');
            $this->updatePrintStatusOptional($tokenIds);

            // Save the PDF filename in the database
            $this->savePdfFilenameToDatabaseOptional($event_id, $filename);

            // Provide the link to download the PDF file
            if (file_exists($backupFilename)) {
                // Download the PDF file
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment;filename=' . basename($backupFilename));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($backupFilename));
                ob_clean();
                flush();
            
                // Read the file and output its contents
                readfile($backupFilename);
        
                exit();
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
                header('Location: ../events-details');
                exit();
            }
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Access token are printed already, try to generate again thank you!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
            header('Location: ../events-details');
            exit();
        }
    }

    public function updatePrintStatusOptional($tokenIds)
    {
        // Update the print_status of the given token IDs to "printed"
        $placeholders = implode(',', array_fill(0, count($tokenIds), '?'));
        $sql = "UPDATE access_token SET print_status = 'printed' WHERE id IN ($placeholders)";
        $stmt = $this->runQuery($sql);
        $stmt->execute($tokenIds);
    }

    public function savePdfFilenameToDatabaseOptional($event_id, $filename)
    {
        $stmt = $this->runQuery('INSERT INTO pdf_file (event_id, file_name) VALUES (:event_id, :file_name)');
        $exec = $stmt->execute(array(
            ":event_id"  => $event_id,
            ":file_name"         => $filename,
        ));
    
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Add access tokens Optional
if (isset($_POST['btn-add-access-token-optional'])) {
    $user_id = $_GET["user_id"];
    $event_id = $_GET["event_id"];
    $number_of_tokens = trim($_POST['access_token']);

    $add_access_token = new AccessTokenOptional();
    $generated_tokens = $add_access_token->addAccessTokenoOptional($user_id, $event_id, $number_of_tokens);
}

// Generate access tokens PDF and provide download link Optional
if (isset($_GET['print_access_tokens_optional'])) {
    $event_id = $_GET["event_id"];

    $print_access_token = new AccessTokenOptional();
    $print_access_token->generateAccessTokensPDFOptional($event_id);
}
?>
