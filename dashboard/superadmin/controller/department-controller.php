<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class Department {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add department
    public function addDepartment($department_name, $department_logo){
        // Check if the department name already exists
        if ($this->isDepartmentNameExists($department_name)) {
            $_SESSION['status_title'] = 'Error';
            $_SESSION['status'] = 'Department with the same name already exists';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
            header('Location: ../department');
            return; // Exit the function
        }
    
        $folder = "../../../src/img/" . basename($department_logo);
        chmod($folder, 0755);
        $stmt = $this->runQuery('INSERT INTO department (department, department_logo) VALUES (:department, :department_logo)');
        $exec = $stmt->execute(array(
            ":department"       => $department_name,
            ":department_logo"  => $department_logo,
        ));
    
        if ($exec  && move_uploaded_file($_FILES['department_logo']['tmp_name'], $folder)) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Department added successfully';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    
        header('Location: ../department');
    }
    
    public function isDepartmentNameExists($department_name)
    {
        $stmt = $this->runQuery('SELECT COUNT(*) FROM department WHERE department = :department_name');
        $stmt->execute(array(":department_name" => $department_name));
        $count = $stmt->fetchColumn();
    
        return ($count > 0);
    }  
//edit department
public function editDepartment($department_id, $department_name, $department_logo) {
    // Retrieve the current department name from the database
    $stmt = $this->runQuery('SELECT department FROM department WHERE id=:id');
    $stmt->execute(array(":id" => $department_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_department_name = $row['department'];

    // Check if the department name has changed
    if ($department_name != $current_department_name) {
        $stmt = $this->runQuery('UPDATE department SET department=:department WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $department_id,
            ":department" => $department_name,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Department name successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    } else {
        $_SESSION['status_title'] = 'No Changes';
        $_SESSION['status'] = 'No changes were made to the department name.';
        $_SESSION['status_code'] = 'info';
        $_SESSION['status_timer'] = 40000;
    }

    // Check if a new department logo file is provided
    if (!empty($_FILES['department_logo']['tmp_name'])) {
        $folder = "../../../src/img/" . basename($department_logo);
        chmod($folder, 0755);

        $stmt = $this->runQuery('UPDATE department SET department_logo=:department_logo WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id" => $department_id,
            ":department_logo" => $department_logo,
        ));

        if ($exec && move_uploaded_file($_FILES['department_logo']['tmp_name'], $folder)) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Department logo successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }
    }

    header('Location: ../department');
    exit();
}


    //delete department
    public function deleteDepartment($department_id){
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE department SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $department_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Department successfully deleted!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../department');
        exit();

    }

    //activate department
    public function activateDepartment($department_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE department SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $department_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Department successfully activated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../department');
    }


    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//add
if (isset($_POST['btn-add-department'])) {
    $department_name    = trim($_POST['department']);
    $department_logo    = $_FILES['department_logo']['name'];

    $add_department = new Department();
    $add_department->addDepartment($department_name, $department_logo);
}

//edit
if (isset($_POST['btn-edit-department'])) {
    $department_id     = $_GET["id"];
    $department_name    = trim($_POST['department']);
    $department_logo    = $_FILES['department_logo']['name'];

    $edit_department = new Department();
    $edit_department->editDepartment($department_id, $department_name, $department_logo );
}

//delete
if (isset($_GET['delete_department'])) {
    $department_id = $_GET["id"];

    $delete_department = new Department();
    $delete_department->deleteDepartment($department_id);
}

//activate
if (isset($_GET['activate_department'])) {
    $department_id = $_GET["id"];

    $activate_department = new Department();
    $activate_department->activateDepartment($department_id);
}



?>