<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class YearLevel {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add year level
    public function addyearLevel($year_level_name){
        $stmt = $this->runQuery('INSERT INTO year_level (year_level) VALUES (:year_level)');
        $exec = $stmt->execute(array(
            ":year_level"  => $year_level_name
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Year Level added successfully';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../year-level');
    }
    
    //edit year level
    public function edityearLevel($year_level_id, $year_level_name) {
        // Check if the year level name and department have actually changed
        $old_data_stmt = $this->runQuery('SELECT year_level FROM year_level WHERE id=:id');
        $old_data_stmt->execute(array(
            ":id" => $year_level_id,
        ));
        $old_data = $old_data_stmt->fetch(PDO::FETCH_ASSOC);
        $old_name = $old_data['year_level'];
        
        if ($old_name == $year_level_name ) {
            // year level name  have not changed, don't need to update
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'No changes were made.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;
            
            header('Location: ../year-level');
            exit();
        }
        
        // year level name  has changed, execute UPDATE query
        $stmt = $this->runQuery('UPDATE year_level SET year_level=:year_level WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $year_level_id,
            ":year_level" => $year_level_name,
        ));
        
        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Year Level successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
        
        header('Location: ../year-level');
        exit();
    }    

    //delete year level
    public function deleteyearLevel($year_level_id){
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE year_level SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $year_level_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Year Level successfully deleted!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../year-level');
        exit();

    }

    //activate year level
    public function activateyearLevel($year_level_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE year_level SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $year_level_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Year Level successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../year-level');
    }

        //permanent delete year level
        public function permanentDeleteyearLevel($year_level_id){
            $stmt = $this->runQuery('DELETE FROM year_level WHERE id=:id');
            $exec = $stmt->execute(array(
                ":id" => $year_level_id
            ));
        
            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Year Level successfully deleted!';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        
            header('Location: ../archives/year-level');
            exit();
        }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//add
if (isset($_POST['btn-add-year-level'])) {
    $year_level_name     = trim($_POST['year_level']);

    $add_year_level = new YearLevel();
    $add_year_level->addyearLevel($year_level_name);
}

//edit
if (isset($_POST['btn-edit-year-level'])) {
    $year_level_id       = $_GET["id"];
    $year_level_name     = trim($_POST['year_level']);

    $edit_year_level = new YearLevel();
    $edit_year_level->edityearLevel($year_level_id, $year_level_name);
}

//delete
if (isset($_GET['delete_year_level'])) {
    $year_level_id = $_GET["id"];

    $delete_year_level = new YearLevel();
    $delete_year_level->deleteyearLevel($year_level_id);
}

//activate
if (isset($_GET['activate_year_level'])) {
    $year_level_id = $_GET["id"];

    $activate_year_level = new YearLevel();
    $activate_year_level->activateyearLevel($year_level_id);
}

//permanent delete
if (isset($_GET['permanent_delete_year_level'])) {
    $year_level_id = $_GET["id"];

    $permanent_delete_year_level = new YearLevel();
    $permanent_delete_year_level->permanentDeleteyearLevel($year_level_id);
}



?>