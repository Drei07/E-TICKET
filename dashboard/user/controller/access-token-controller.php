<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class AccessToken {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function addAccessToken($user_id, $event_id, $number_of_tokens)
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

            if (!$this->isTokenExists($accessToken)) {
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

    public function isTokenExists($accessToken)
    {
        $stmt = $this->runQuery('SELECT COUNT(*) FROM access_token WHERE token = :token');
        $stmt->execute(array(':token' => $accessToken));
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Add access tokens
if (isset($_POST['btn-add-access-token'])) {
    $user_id = $_GET["user_id"];
    $event_id = $_GET["event_id"];

    $number_of_tokens = trim($_POST['access_token']);

    $add_access_token = new AccessToken();
    $generated_tokens = $add_access_token->addAccessToken($user_id, $event_id, $number_of_tokens);
}

?>